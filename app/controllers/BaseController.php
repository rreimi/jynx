<?php

class BaseController extends Controller {

    protected $pageSort;
    protected $pageOrder;
    protected $sliderSize = 12;
    protected static $thumbSize = array('width' => 200, 'height' => 200);
    protected static $detailSize = array('width' => 450, 'height' => 450);
    protected static $logoSize = array('width' => 130, 'height' => 130);
    protected static $bannerTopHomeSize = array('width' => 1170, 'height' => 390);

    protected $phoneNumberRegex = '/^(04[16|26|14|24|12]{2})?(02[0-9]{2})?-[0-9]{7}$/';

    public function __construct(){
        //die();
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    public static function getCategories() {
        $value = Cache::rememberForever('categoryTree', function()
        {
            return Category::getCategoryTree();
        });

        return $value;

//        $categories = Category::parents()->with('subcategories')->orderByName()->get();
//        $categories->load('subcategories');
//        foreach ($categories as $category) {
//            $subcats = array();
//            foreach ($category->subcategories as $subcat) {
//                $subcats[] = $subcat;
//            }
//            usort($subcats, function($a, $b) { return strtolower($b->name) < strtolower($a->name); });
//            $category->subcategories = $subcats;
//        }
//        return $categories;
    }

    public static function getProducts() {
        $value = Cache::rememberForever('productsTree', function()
        {
            return Category::getCategoryTree('Product');
        });

        return $value;
    }

    public static function getServices() {
        $value = Cache::rememberForever('servicesTree', function()
        {
            return Category::getCategoryTree('Service');
        });

        return $value;

//        $categories = Category::parents()->with('subcategories')->orderByName()->get();
//        $categories->load('subcategories');
//        foreach ($categories as $category) {
//            $subcats = array();
//            foreach ($category->subcategories as $subcat) {
//                $subcats[] = $subcat;
//            }
//            usort($subcats, function($a, $b) { return strtolower($b->name) < strtolower($a->name); });
//            $category->subcategories = $subcats;
//        }
//        return $categories;
    }

    protected function addFlashMessage($title, $message, $type = 'success'){
        $object = new stdClass;
        $object->title = $title;
        $object->message = $message;
        $object->type = $type;
        Session::flash('flash_global_message', json_encode($object));
    }

    protected function sendMail($template, $data, $receivers, $subject){

       Mail::send($template, $data, function($message) use ($receivers, $subject){
            $message->from(Config::get('emails/addresses.no_reply'), Config::get('emails/addresses.company_name'));
            $message->to($receivers['email'], $receivers['name'])->subject($subject);
        });
    }

    protected function sendMultipleMail($template, $data, $receivers, $subject){

        Mail::send($template, $data, function($message) use ($receivers, $subject){
            $message->from(Config::get('emails/addresses.no_reply'), Config::get('emails/addresses.company_name'));
            $message->to($receivers['email'])->subject($subject);
        });
    }

    protected function sendMailAdmins($template, $data, $subject){

        $receivers = self::getEmailAdmins();

        Mail::send($template, $data, function($message) use ($receivers, $subject){
            $message->from(Config::get('emails/addresses.no_reply'), Config::get('emails/addresses.company_name'));
            $message->to($receivers);
            $ccoAdminEmails = Config::get('emails/addresses.cco_admin');
            if ($ccoAdminEmails != null){
                $message->bcc(explode(',', $ccoAdminEmails));
            }
            $message->subject($subject);
        });
    }

    public static function sendAjaxMail($template, $data, $receivers, $subject){

        Mail::send($template, $data, function($message) use ($receivers, $subject){
            $message->from(Config::get('emails/addresses.no_reply'), Config::get('emails/addresses.company_name'));
            $message->to($receivers['email'], $receivers['name'])->subject($subject);
        });
    }

    protected function getEmailAdmins(){
        $adminUsers = User::adminEmailList()->get();

        $adminEmails = array();

        foreach ($adminUsers as $adminU){
            $adminEmails[] = $adminU->email;
        }

        return $adminEmails;
    }

    public function sendSuspendedEmail($email, $fullName){
        $receiver = array(
            'email' => $email,
            'name' => $fullName,
        );

        $data = array(
            'contentEmail' => 'user_suspended',
            'userName' => $fullName,
        );

        $subject = Lang::get('content.email_user_suspended');

        self::sendMail('emails.layout_email', $data, $receiver, $subject);
    }

    public function saveAvatar($avatar, $user){

        $fileOperation = 'success';

        if($avatar != null && $user != null){

            try {
                $size = getimagesize($avatar);
                $fileFinalName = 'avatar-'. $user->id .'.jpg';
                $destinationPath = 'uploads/profile/';

                ImageHelper::generateThumb($avatar->getPathName(), $destinationPath . 'avatar-'. $user->id . '.jpg',  $size[0],  $size[1]);
                ImageHelper::generateThumb($avatar->getPathName(), $destinationPath . 'avatar-'. $user->id . '_' . BaseController::$thumbSize['width'] . '.jpg',  BaseController::$thumbSize['width'],  BaseController::$thumbSize['height']);

                $publisher = $user->publisher;
                $publisher->avatar = $destinationPath . $fileFinalName;
                $publisher->save();
            } catch (Exception $e){
                $fileOperation = 'error';
            }

        }

        return $fileOperation;

    }

    public function deleteAvatar($publisher){

        $fileOperation = 'success';

        $avatar = $publisher->avatar;
        $publisher->avatar = null;
        $publisher->save();

        try {
            File::delete($avatar);
        } catch (Exception $e){
            $fileOperation = 'error-delete';
        }

        return $fileOperation;
    }

}