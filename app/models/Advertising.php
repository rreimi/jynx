<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Advertising extends Eloquent {

    protected $softDelete = true;

    protected $fillable = array('name', 'status',
        'image_url', 'external_url', 'first_name', 'last_name',
        'email', 'phone1', 'phone2');

}