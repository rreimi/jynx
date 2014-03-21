<?php

class PublicationView extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'publications_view';
    protected $softDelete = true;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

    public function scopePublished($query){
        return $query->where('status', 'Published');
    }

    public function scopeFilter($query, $filters) {
        if (isset($filters['state'])){
            $query->where('state_id', $filters['state']->id);
        }

        if (isset($filters['seller'])){
            $query->where('publisher_id', $filters['seller']->id);
        }

        if (isset($filters['category'])){
            $query->where('category_id', $filters['category']->id);
        }

        return $query;
    }

    public function scopePublishersWithPublications($query) {
        $query->select('publisher_id', 'seller_name', DB::raw('count(*) as publications_number'))->groupBy('publisher_id');
    }

    public function scopeCategoriesWithPublications($query) {

        // las categorias de las publicaciones
        $query->select('categories.id as category_id', 'categories.name as category_name', DB::raw('count(publications_view.id) as publications_number'));
        $query->leftJoin('publications_categories','publications_view.id','=','publications_categories.publication_id');
        $query->leftJoin('categories','publications_categories.category_id','=','categories.id');
        $query->groupBy('categories.id');

        /*
        SELECT COUNT( pv.id ) AS total, c.id AS category_id, c.name
        FROM publications_view pv
        JOIN publications_categories pc ON pc.publication_id = pv.id
        JOIN categories c ON pc.category_id = c.id
        GROUP BY c.id */
    }

    public function scopeWithCategories($query, $categories) {
        $query->leftJoin('publications_categories','publications_view.id','=','publications_categories.publication_id');
        $query->whereIn('publications_categories.category_id', $categories);
    }


    public function scopeParents($query) {
        return $query->where('category_id', '=', null);
    }

    public function categories() {
        return $this->belongsToMany('Category', 'publications_categories');
    }

    public function images() {
        return $this->hasMany('PublicationImage', 'publication_id');
    }


    public function publisher() {
        return $this->belongsTo('Publisher');
    }

    public static function getSearch($q, $orderBy = 'visits_number', $orderDir = 'desc') {
        $query = PublicationView::orderBy($orderBy, $orderDir);
        $tokens = explode(' ', $q);
        foreach ($tokens as $token) {
            $query->Where(function($query) use ($token)
            {
                $query->orWhere('title', 'like', "%$token%")
                    //->orWhere('short_description', 'like', "%$token%")
                    ->orWhere('long_description', 'like', "%$token%")
                    ->orWhere('seller_name', 'like', "%$token%")
                    ->orWhere('categories', 'like', "%$token%")
                    ->orWhere('city', 'like', "%$token%")
                    ->orWhere('state', 'like', "%$token%")
                    ->orWhere('contacts_state', 'like', "%$token%")
                    ->orWhere('contacts_city', 'like', "%$token%")
                    ->orWhere('contacts', 'like', "%$token%");
            });
        }

        return $query;
    }

    public static function getSearchQuery($q, $fields = '*', $filters = array()) {

        $query = "SELECT $fields from publications_view ";

        $wheres = 0;

        if ($q != "") {
            $tokens = explode(' ', $q);
            if (count($tokens) > 0) {
                $query .= "WHERE (";
                $wheres++;
                $c = 0;
                foreach ($tokens as $token) {

                    if ($c > 0){
                        $query .= " OR";
                    }

                    $query .= " title LIKE '%$token%' OR";
                    $query .= " long_description LIKE '%$token%' OR";
                    $query .= " seller_name LIKE '%$token%' OR";
                    $query .= " categories LIKE '%$token%' OR";
                    $query .= " city LIKE '%$token%' OR";
                    $query .= " contacts_state LIKE '%$token%' OR";
                    $query .= " contacts_city LIKE '%$token%' OR";
                    $query .= " contacts LIKE '%$token%'";
                }
                $query .= ")";
            }
        }

        if ($wheres == 0) {
            $wheres++;
            $query .= ' WHERE ';
        } else {
            $query .= ' AND ';
        }

        $query .= " status = '" . Publication::STATUS_PUBLISHED . "'";
        /* Filters */

        if (count($filters) > 0) {

            if (isset($filters['state'])){
                if ($wheres == 0) {
                    $wheres++;
                    $query .= ' WHERE ';
                } else {
                    $query .= ' AND ';
                }
                $query .= ' FIND_IN_SET (' . intval($filters['state']) . ',contacts_states_id)';
            }

            if (isset($filters['category'])){
                if ($wheres == 0) {
                    $wheres++;
                    $query .= ' WHERE ';
                } else {
                    $query .= ' AND ';
                }
                $query .= ' FIND_IN_SET (' . intval($filters['category']) . ',categories_id)';
            }

            if (isset($filters['seller'])){

                if ($wheres == 0) {
                    //$wheres++; uncomment if there is a new condition
                    $query .= ' WHERE ';
                } else {
                    $query .= ' AND ';
                }

                $query .= ' publisher_id = ' . intval($filters['seller']);
            }
        }

        $activeFilters['order_by'] = 'updated_at';
        $activeFilters['order_dir'] = 'desc';

        /* Group by */
        if (isset($filters['group_by'])) {
            $query .= " GROUP BY " . $filters['group_by'];
        }

        if (isset($filters['order_by'])) {
            $query .= " ORDER BY " . $filters['order_by'];

            if (isset($filters['order_dir'])) {
                $query .= ' ' . $filters['order_dir'];
            }
        }

        //Pagination
        if (isset($filters['page_size'])) {
            $query .= " LIMIT ";

            if (isset($filters['offset'])) {
                $query .= $filters['offset'] . ',' . $filters['page_size'];
            } else {
                $query .= $filters['page_size'];
            }
        }

        return $query;
    }

}