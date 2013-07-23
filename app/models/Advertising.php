<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Advertising extends Eloquent {

    protected $softDelete = true;

    protected $fillable = array('name', 'status',
                                'image_url', 'external_url', 'full_name',
                                'email', 'phone1', 'phone2');

    public function scopeActivehomeadvertisings($query){
        $query->where('category_id', '=', null)
              ->where('status', '=', 'Published')
              ->orderBy('updated_at', 'desc');
    }


}