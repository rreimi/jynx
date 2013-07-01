<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Publisher extends Eloquent {

    public function sectors() {
        return $this->belongsToMany('BusinessSector', 'publishers_sectors');
    }

}