<?php

class MyDirectoryController extends DirectoryController {

    public function __construct() {
        $this->beforeFilter('auth');
//        $this->beforeFilter('referer:advertiser', array('only' => array('getLista', 'getDetalle')));
//        View::share('categories', self::getCategories());
//        View::share('products', self::getProducts());
//        View::share('services', self::getServices());
    }

    public function getIndex() {
        //Calculate state
        $listState = self::retrieveListState();
        $listState['is_my_directory'] = true;

        //Do the search
        $result = Publisher::getSearch($listState);
        $publishers = Paginator::make($result->searchResults, $result->totalResults, $listState['page_size']);

        //Required master data
        $states = State::lists("name","id");

        $sidebarExcludedParams = array();

        //If country is an available filter, clear the state filter as well
        if (isset($result->availableFilters['country'])) {
            $sidebarExcludedParams[] = 'state';
        }

        return View::make("directory", array(
                'advertisers' => $publishers,
                'states' => $states,
                'availableFilters' => $result->availableFilters,
                'activeFilters' => $listState['filters'],
                'searchString' => $listState['q'],
                'formAction' => "directorio",
                'isMyDirectory' => true,
                'sidebarExcludedParams' => $sidebarExcludedParams
            )
        );
    }

    public function postIndex() {
        return $this->getIndex();
    }

    /**
     * Agregar un anunciante a mi directorio
     * @param $publisherId
     */
    public function getAgregar($publisherId) {
        $userId = Auth::user()->id;
        $publisherId = intval($publisherId);
        $result = MyDirectory::addToMyDirectory($userId,$publisherId);

        //TODO ajax response for not auth
        if ($result == MyDirectory::STATUS_OK) {
            return Response::json(null, 200);
        } else {
            return Response::json(null, 400);
        }
    }

    /**
     * Quitar un anunciante de mi directorio
     * @param $publisherId
     */
    public function getQuitar($publisherId) {
        $userId = Auth::user()->id;
        $publisherId = intval($publisherId);
        $result = MyDirectory::removeFromMyDirectory($userId, $publisherId);

        //TODO ajax response for not auth
        if ($result == MyDirectory::STATUS_OK) {
            return Response::json(null, 200);
        } else {
            return Response::json(null, 400);
        }
    }
}
