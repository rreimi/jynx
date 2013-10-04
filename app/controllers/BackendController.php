<?php

class BackendController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('admin');
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
                }else{
                    User::whereIn('id',$users)->update(array('is_publisher'=>0));
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

                } else {

                    $data['contentEmail'] = 'disapproved_user_notification';

                    $subject = Lang::get('content.email_disapproved_user_notification');

                    self::sendMail('emails.layout_email', $data, $receiver, $subject);

                }
            }
        }
        return Redirect::to('dashboard');
    }

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

                    $ext = pathinfo($originalFile, PATHINFO_EXTENSION);

                    //Copiar el archivo viejo al nuevo


                    //Generar thumbs
                    $size = getimagesize($originalFile);

                    $fullSizeFileName = $destinationPath . $baseName . '.jpg';
                    $detailFileName = $destinationPath . $baseName . '_' . BaseController::$detailSize['width'] . '.jpg';
                    $thumbFileName = $destinationPath . $baseName . '_' . BaseController::$thumbSize['width'] . '.jpg';

                    //ImageHelper::generateThumb($originalFile, $fullSizeFileName, $size[0], $size[1]);
                    //ImageHelper::generateThumb($originalFile, $detailFileName,  BaseController::$detailSize['width'],  BaseController::$detailSize['height']);
                    //ImageHelper::generateThumb($originalFile, $thumbFileName, BaseController::$thumbSize['width'], BaseController::$thumbSize['height']);

                    //Actualizar publication images
                    //$img->image_url = $baseName . '.jpg'
                    //$img->save();

                    //Borrar la imagen original
                    var_dump($originalFile);
                    var_dump($fullSizeFileName);
                    var_dump($detailFileName);
                    var_dump($thumbFileName);
                }
            }

        }

        die();

        $file = Input::file('file');
        $destinationPath =  public_path() . '/uploads/pub/'.$id;
        $filename = $file->getClientOriginalName();

        $publication = Publication::find($id);

        //TODO validar publicacion
        //TODO renombrar la imagen si existe
        //TODO posibilidad de agregar un alt
        $size = getimagesize($file);

        $upload_success = false;
        $error = '';

        //Validate image size

        if ($size[0] < BaseController::$detailSize['width'] ) {
            $error = 'invalid_size';
        }

        if ($size[1] < BaseController::$detailSize['height']) {
            $error = 'invalid_size';
        }

        $baseName = str_random(15);

        $finalFileName = $baseName . '.jpg';
        $detailFileName = $destinationPath . '/' . $baseName . '_' . BaseController::$detailSize['width'] . '.jpg';
        $thumbFileName = $destinationPath . '/' . $baseName . '_' . BaseController::$thumbSize['width'] . '.jpg';

        if (empty($error)){
            ImageHelper::generateThumb($file->getPathName(), $detailFileName,  BaseController::$detailSize['width'],  BaseController::$detailSize['height']);
            ImageHelper::generateThumb($file->getPathName(), $thumbFileName, BaseController::$thumbSize['width'], BaseController::$thumbSize['height']);
            $upload_success = $file->move($destinationPath, $finalFileName);

            // Using intervention
//            $data = file_get_contents($file);
//
//            $img = Image::make($data);
//            $img->save( $destinationPath . '/' . $finalFileName, 90);
//
//            $detail = Image::make($data)->resize(BaseController::$detailSize['width'], null, true);
//            $detail->save( $destinationPath . '/' . $detailFileName, 80);
//
//            $thumb = Image::make($data)->resize(BaseController::$thumbSize['width'], null, true);
//            $thumb->save( $destinationPath . '/' . $thumbFileName, 80);
//            End using intervention

        }

    }
}