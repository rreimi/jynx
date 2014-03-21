<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Contact extends Eloquent {

    protected $softDelete = true;

    public function sectors() {
        return $this->belongsToMany('BusinessSector', 'publishers_sectors');
    }

    public function contacts() {
        return $this->hasMany('Contact');
    }

    public function state() {
        return $this->belongsTo('State');
    }

    public function isMainContact(){
        return ($this->is_main === '');
    }
}