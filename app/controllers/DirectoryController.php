<?php

class DirectoryController extends BaseController {

    protected $prefix = 'directory';
    protected $pageSize = '10';
    protected $listSort = array('seller_name', 'city');
    protected $defaultSort = 'seller_name';
    protected $defaultOrder = 'asc';

    public function __construct() {

    }

    public function getIndex() {
        //Calculate state
        $listState = self::retrieveListState();

        //Do the search
        $result = Publisher::getSearch($listState);
        $publishers = Paginator::make($result->searchResults, $result->totalResults, $listState['page_size']);

        //Required master data
        $states = State::lists("name","id");

        //Get the ids of publishers added by the current user to his directory
        $myDirectoryEntries = Array();
        if (Auth::check()) {
            $myDirectoryEntries = MyDirectory::ofUser(Auth::user()->id)->lists('publisher_id');
        }

        return View::make("directory", array(
                'advertisers' => $publishers,
                'states' => $states,
                'availableFilters' => $result->availableFilters,
                'activeFilters' => $listState['filters'],
                'searchString' => $listState['q'],
                'formAction' => "directorio",
                'myDirectoryEntries' => $myDirectoryEntries
            )
        );
    }

    public function postIndex() {
        return $this->getIndex();
    }

    /**
     * Get the current list state based on query parameters
     *
     * @return array
     */
    protected function retrieveListState(){
        $state = Array();
        $state['filters'] = Array();

        //Sort and Order
        $state['sort'] = (in_array(Input::get('sort'), $this->listSort) ? Input::get('sort') : $this->defaultSort);
        $state['order'] = (in_array(Input::get('order'), array('asc', 'desc')) ? Input::get('order') : $this->defaultOrder);

        //Pagination
        $state['page'] = Input::get('page', 1);
        $state['page_size'] = Input::get('page_size', $this->pageSize);

        //Search String
        $state['q'] = Input::get('q', '');

        if (Input::get('state')) {
            $location = State::find(Input::get('state'));
            if (!is_null($location)){
                $locationFilter = new \stdClass;
                $locationFilter->value  = $location->id;
                $locationFilter->label = $location->name;
                $locationFilter->type = 'state';
                $state['filters'][] = $locationFilter;
            }
        }

        if (Input::get('category')) {
            $category = Category::getFromCache(Input::get('category'));
            if (!is_null($category)) {
                $categoryFilter = new \stdClass;
                $categoryFilter->value  = $category->id;
                $categoryFilter->label = $category->name;
                $categoryFilter->type = 'category';
                $state['filters'][] = $categoryFilter;
            }
        }

        return $state;
    }
}
