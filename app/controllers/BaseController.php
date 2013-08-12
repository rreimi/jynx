<?php

class BaseController extends Controller {

    protected $pageSort;
    protected $pageOrder;
    protected static $thumbSize = array('width' => 150, 'height' => 150);
    protected static $detailSize = array('width' => 300, 'height' => 300);
    protected static $bannerTopHomeSize = array('width' => 870, 'height' => 250);


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

    protected function getCategories() {

//        Cache::forget('categoryTree');
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

    protected function getServices() {

//        Cache::forget('servicesTree');
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

}