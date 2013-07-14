<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class PublicationImage extends Eloquent {


    protected $fillable = array('image_url');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	//protected $table = 'categories';

    public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

}