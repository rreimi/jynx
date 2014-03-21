<?php

class HomePublicationView extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'home_publications_view';
    public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

    public function scopeMostVisited($query, $limit){
        return $query->orderBy('last_days_visits', 'desc')->take($limit);
    }

    public function scopeRecent($query, $limit){
        $query->orderBy('created_at', 'desc')->take($limit);
    }

    public static function getCategoryQuery($fields = '*', $filters = array()) {
        $query = "SELECT $fields from home_publications_view ";

        $wheres = 0;

        $query .= " WHERE status = '" . Publication::STATUS_PUBLISHED . "'";
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