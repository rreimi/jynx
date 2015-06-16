<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Publisher extends Eloquent {

    protected $softDelete = true;

    const STATUS_PENDING="Pending";

    const STATUS_APPROVED="Approved";

    const STATUS_DENIED="Denied";

    const STATUS_SUSPENDED="Suspended";

    protected $fillable = array('publisher_type', 'seller_name',
        'letter_rif_ci', 'rif_ci', 'state_id', 'city',
        'media', 'phone1', 'phone2');

    public function scopeStatusApproved($query){
        $query->where('publishers.status_publisher', '=', self::STATUS_APPROVED);
    }

    public function scopeSubAdmin($query) {
        if (Auth::user()->isSubAdmin()) {
            $query->join('users', 'users.id', '=', 'publishers.user_id')
                ->where('users.group_id', Auth::user()->group_id);
        }
    }

    public function scopeWithPublications($query){
        $query->select('publishers.id', 'publications.id', 'publications.publisher_id')
              ->join('publications','publishers.id','=','publications.publisher_id');
        if (Auth::user()->isSubAdmin()){
            $query->join('users', 'users.id', '=', 'publishers.user_id')
                  ->where('group_id', Auth::user()->group_id);
        }
        $query->groupBy('publications.publisher_id');
    }

    public function scopeAllRows($query){
        if (Auth::user()->isSubAdmin()){
            $query->join('users', 'users.id', '=', 'publishers.user_id')
                  ->where('group_id', Auth::user()->group_id);
        }
    }

    public function sectors() {
        return $this->belongsToMany('BusinessSector', 'publishers_sectors');
    }

    public function contacts() {
        return $this->hasMany('Contact');
    }

    public function state() {
        return $this->belongsTo('State');
    }

    public function categories(){
        return $this->belongsToMany('Category','publishers_categories');
    }

    public function user(){
        return $this->belongsTo('User');
    }

    public function getMainContact(){
        return Contact::where('publisher_id', $this->id)->where('is_main', '')->first();
    }
    //after save create contact :D

    public static function getSearch($listState, $fields = 'p.*,u.email') {

        /** @var $pdo \PDO */
        $pdo = DB::connection()->getPdo();

        $activeFilters = Array();

        $selectClause = "SELECT {fields} FROM publishers p LEFT JOIN users u ON u.id = p.user_id";

        $isWhere = true;
        $whereClause = "";
        $orderByClause = "";
        $params = Array();

        if (!empty($listState['q'])) {
            $tokens = explode(' ', $listState['q']);
            if (count($tokens) > 0) {
                $whereClause .= " WHERE (";
                $isWhere = false;
                $c = 0;
                foreach ($tokens as $token) {
                    if ($c > 0) { $whereClause .= " OR"; }
                    $whereClause .= " p.seller_name LIKE :token_$c";
                    $whereClause .= " OR p.city LIKE ':token_$c'";
                    $whereClause .= " OR u.email LIKE ':token_$c'";
                    $whereClause .= " OR p.address LIKE ':token_$c'";
                    $whereClause .= " OR p.phone1 LIKE ':token_$c'";
                    $whereClause .= " OR p.phone2 LIKE ':token_$c'";
                    $params[":token_$c"] = "%$token%";
                    $c++;
                }
                $whereClause .= ")";
            }
        }

        if (isset($listState['filters'])) {
            foreach ($listState['filters'] as $filter) {
                if ($filter->type == 'country') {
                    $whereClause .= ($isWhere? " WHERE " : " AND " ) . "p.country_id = :country_id";
                    $isWhere = false;
                    $params[":country_id"] = $filter->value;
                    $activeFilters[] = 'country';

                }

                if ($filter->type == 'state') {
                    $whereClause .= ($isWhere? " WHERE " : " AND " ) . "p.state_id = :state_id";
                    $isWhere = false;
                    $params[":state_id"] = $filter->value;
                    $activeFilters[] = 'state';

                }

                if ($filter->type == 'category') {
                    $selectClause .= " JOIN publishers_categories pc ON pc.publisher_id = p.id";
                    $whereClause .= ($isWhere? " WHERE " : " AND " ) . "pc.category_id = :category_id";
                    $isWhere = false;
                    $params[":category_id"] = $filter->value;
                    $activeFilters[] = 'category';
                }
            }
        }

        if (Auth::check()) {
            if (isset($listState['is_my_directory'])) {
                $selectClause .= " JOIN my_directory md ON md.publisher_id = p.id AND md.user_id = :user_id" ;
                $params[":user_id"] = Auth::user()->id;

            }
        }

        //Add default filters
        $whereClause .=  ($isWhere? " WHERE " : " AND " ) . "p.deleted_at is null";
        $isWhere = false;
        $whereClause .=  ($isWhere? " WHERE " : " AND " ) . "p.status_publisher = 'Approved'";
        $isWhere = false;
        $whereClause .=  ($isWhere? " WHERE " : " AND " ) . "u.role <> 'Basic'";

//        //Add query sort and order
        if (isset($listState['sort'])) {
            $orderByClause .= " ORDER BY " . $listState['sort'];
            if (isset($listState['order']) && in_array($listState['order'], Array('asc','desc'))) {
                $orderByClause .= " " . $listState['order'];
            }
        }

        $finalQuery = str_replace("{fields}", $fields, $selectClause) . $whereClause . $orderByClause;
        $facetedInQuery = str_replace("{fields}", "p.id", $selectClause) . $whereClause . $orderByClause;

        //Pagination //TODO condition based on page size
        $countQuery = str_replace("{fields}", "COUNT(*)", $selectClause) . $whereClause . $orderByClause;

        //Add pagination to the final query (faceted query needs all results)
        $total = 0;
        try {
            $countStmt = $pdo->prepare($countQuery);

            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetchColumn();
        } catch (PDOException $ex) {
            die();
            //TODO capture exception in the proper way
        }

        $offset = ($listState['page'] - 1) * $listState['page_size'];
        $offset = ($offset < $total)? $offset : 0;
        $pageSize = isset($listState['page_size'])? $listState['page_size'] : 0;

        //Pagination
        if ($pageSize > 0) {
            $finalQuery .= " LIMIT ";
            if (isset($offset)) {
                $finalQuery .= $offset . ',' . $pageSize;
            } else {
                $finalQuery .= $pageSize;
            }
        }

        $results = Array();

        try {
            $stmt = $pdo->prepare($finalQuery);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            var_dump($ex);
            //TODO capture exception in the proper way
        }

        //If country is not selected, mark state as an active filter in order to avoid get it displayed
        if (!in_array('country', $activeFilters)) {
            $activeFilters[] = 'state';
        }


        $activeFiltersIn = self::buildStringInClause($activeFilters);
        //$activeFiltersIn = implode(",", $activeFilters);
        $facetedQuery = "SELECT label, value as item_id, type, count(value) as total FROM publishers_faceted_search_view as pfac WHERE publisher_id IN ($facetedInQuery) AND type not in ($activeFiltersIn) group by type, value";

        //TODO handle exception here
        $facetedStmt = $pdo->prepare($facetedQuery);
        $facetedStmt->execute($params);
        $facetedResults = $facetedStmt->fetchAll(PDO::FETCH_OBJ);



        $result = new \stdClass;
        $result->searchResults = $results;
        $result->availableFilters = self::getFacetedAssoc($facetedResults);
        $result->totalResults = $total;

        return $result;
    }

    public static function getFacetedAssoc($facetedResults) {
        $facetedAssoc = Array();
        foreach ($facetedResults as $result) {
            $facetedAssoc[$result->type][] = $result;
        }
        return $facetedAssoc;
    }

    /*
     * Build a string in clause 'a','b','c' from an array of elements
     */
    public static function buildStringInClause($elements) {
        $inClause = "";
        foreach ($elements as $element) {
            $inClause .= "'$element',";
        }

        if (!empty($inClause)) {
            return substr($inClause, 0, strlen($inClause)-1);
        }
        return "''";
    }
}