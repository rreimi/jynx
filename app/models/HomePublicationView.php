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

}