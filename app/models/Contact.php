<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Contact extends Eloquent {

    public function sectors() {
        return $this->belongsToMany('BusinessSector', 'publishers_sectors');
    }

    public function contacts() {
        return $this->hasMany('Contact');
    }

}