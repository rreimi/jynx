<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Publication extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	//protected $table = 'categories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

    public function scopeParents($query) {
        return $query->where('category_id', '=', null);
    }

    public function categories() {
        return $this->belongsToMany('Category');
    }

}