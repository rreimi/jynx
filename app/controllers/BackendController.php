<?php

class BackendController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('admin');
        $this->beforeFilter('csrf', array('only' => array('postMassEmail')));
    }

    public function getIndex() {
        $data['users'] = User::toApprove()->with('publisher')->get();

        $data['reports'] = PublicationReport::pendingReports()->with('user')->with('publication')->get();

        return View::make('backend', $data);
	}

    public function postApprove(){

        $approve= Input::get('approve');

        if(!empty($approve)){
            $users = is_array(Input::get('approve_users'))?Input::get('approve_users'):array();

            if(count($users)>0){
                if($approve=="true"){
                    User::whereIn('id',$users)->update(array('role'=>User::ROLE_PUBLISHER,'is_publisher'=>0));
                    Publisher::whereIn('user_id',$users)->update(array('status_publisher'=>Publisher::STATUS_APPROVED));
                }else{
                    User::whereIn('id',$users)->update(array('is_publisher'=>0));
                    Publisher::whereIn('user_id',$users)->update(array('status_publisher'=>Publisher::STATUS_DENIED));
                }
            }

            // Send email notification to the users when is approved like an advertiser.
            $approvedUsers = User::with('publisher')->whereIn('id', $users)->get();

            foreach ($approvedUsers as $au){

                $receiver = array(
                    'email' => $au->email,
                    'name' => $au->full_name,
                );

                $data = array(
                    'userName' => $au->full_name,
                    'sellerName' => $au->publisher->seller_name,
                    'letterRifCi' => $au->publisher->letter_rif_ci,
                    'rifCi' => $au->publisher->rif_ci,
                );

                if($approve=="true"){

                    $data['contentEmail'] = 'approved_user_notification';

                    $subject = Lang::get('content.email_approved_user_notification');

                    self::sendMail('emails.layout_email', $data, $receiver, $subject);

                    // Log when is approved a publisher by an admin
                    Queue::push('LoggerJob@log', array('method' => null, 'operation' => 'Approve_publisher', 'entities' => array($au),
                        'userAdminId' => Auth::user()->id));

                } else {

                    $data['contentEmail'] = 'disapproved_user_notification';

                    $subject = Lang::get('content.email_disapproved_user_notification');

                    self::sendMail('emails.layout_email', $data, $receiver, $subject);

                    // Log when is disapproved a publisher by an admin
                    Queue::push('LoggerJob@log', array('method' => null, 'operation' => 'Disapprove_publisher', 'entities' => array($au),
                        'userAdminId' => Auth::user()->id));

                }
            }
        }
        return Redirect::to('dashboard');
    }

    public function getMassEmail() {

        $totalPublishers = Publisher::count();
        $extraOptions = array('All' => Lang::get('content.option_all'));

        $data = array(
            'email_content' => '',
            'total_publishers' => $totalPublishers,
            'advertiser_status' => StatusHelper::getStatuses(StatusHelper::$TYPE_ADVERTISER, Lang::get('content.filter_status_placeholder'), $extraOptions)
        );

        return View::make('mass_email', $data);
    }

    public function getAjaxTotalPublishers($type){

        $query = DB::table('publishers')
            ->join('users', 'users.id', '=', 'publishers.user_id');

        // Filter by subAdmin
        if (Auth::user()->isSubAdmin()){
            $query->where('users.group_id', Auth::user()->group_id);
        }

        if ($type != 'All') {
            $query->where('users.status', $type);
        }

        $total = $query->count();

        return json_encode(array('total_publishers' => $total));
    }

    public function postMassEmail() {

        //Get publication data
        $postData = array(
            'email_content' => Input::get('email_content'),
            'status' => Input::get('status'),
            'email_subject' => Input::get('email_subject'),
        );

        //Set validation rules
        $rules = array(
            'email_content' => 'required|max:2000',
            'status' => 'required',
            'email_subject' => 'required|max:120',
        );

        // Validate fields
        $v = Validator::make($postData, $rules);
        if ( $v->fails() ) {
            return Redirect::to('dashboard/mass-email')
                ->withErrors($v)
                ->withInput();
        }

        $query = DB::table('publishers');

        if ($postData['status'] != 'All') {
            $query->select('users.*', 'publishers.seller_name');
            $query->join('users', 'users.id', '=', 'publishers.user_id');
            $query->where('users.status', $postData['status']);
        }

        // Filter by subAdmin group
        if (Auth::user()->isSubAdmin()){
            $query->where('group_id', Auth::user()->group_id);
        }

        $publishers = $query->get();

        foreach ($publishers as $publisher) {
            if (isset($publisher->email)) {
                $emailData = array(
                    'publisherEmail' => $publisher->email,
                    'publisherName' => $publisher->seller_name,
                    'body' => $postData['email_content'],
                    'contentEmail' => 'publisher_mass_email',
                );

                Log::debug('####  Queue -> postMassEmail');
                Log::debug('Data: ' .json_encode($emailData));
                Log::debug('Subject: ' .json_encode($postData));

                Mail::queue('emails.layout_email', $emailData, function($message) use ($emailData, $postData){
                    $message->from(Config::get('emails/addresses.no_reply'), Config::get('emails/addresses.company_name'));
                    $message->to($emailData['publisherEmail'], $emailData['publisherName'])->subject($postData['email_subject']);;
                });
            }
        }

        self::addFlashMessage(null, Lang::get('content.mass_email_sent_success', array('total' => count($publishers))), 'success');
        //Success mensaje

        return Redirect::to('dashboard/mass-email');
    }

    /** @Deprecated used to generate publication images for old publications */
    public function getBatch() {
        $publications = Publication::with('images')->get();
        $basePath = public_path() . '/uploads/pub/';

        foreach ($publications as $pub) {
            if (count($pub->images) > 0) {
                //Publication has images, lets do it baby
                foreach ($pub->images as $img) {

                    //Abrir la imagen original, Generar el base name y el destination path y definir la ruta completa al archivo original
                    $baseName = str_random(15); //nuevo nombre para el archivo
                    $destinationPath = $basePath . $pub->id . '/';
                    $originalFile = $destinationPath . $img->image_url;

                    if (!file_exists($originalFile)){
                        echo 'deleted';
                        var_dump($img);
                        $img->delete();
                        continue;
                    }

                    //Generar thumbs
                    $size = getimagesize($originalFile);

                    $fullSizeFileName = $destinationPath . $baseName . '.jpg';
                    $detailFileName = $destinationPath . $baseName . '_' . BaseController::$detailSize['width'] . '.jpg';
                    $thumbFileName = $destinationPath . $baseName . '_' . BaseController::$thumbSize['width'] . '.jpg';

                    //Copiar el archivo viejo al nuevo
                    ImageHelper::generateThumb($originalFile, $fullSizeFileName, $size[0], $size[1]);

                    //Generar detail
                    ImageHelper::generateThumb($originalFile, $detailFileName,  BaseController::$detailSize['width'],  BaseController::$detailSize['height']);

                    //Generar thumb
                    ImageHelper::generateThumb($originalFile, $thumbFileName, BaseController::$thumbSize['width'], BaseController::$thumbSize['height']);

                    //Actualizar publication images
                    $img->image_url = $baseName . '.jpg';
                    $img->save();

                    if ($pub->publication_image_id == null) {
                        $pub->publication_image_id = $img->id;
                        $pub->save();
                    }

                    //Borrar la imagen original

                    if (file_exists($originalFile)){
                        unlink($originalFile);
                    }

                    echo 'Generated file: '  . $img->image_url . '<br/>';
                }
            }
        }
    }

}