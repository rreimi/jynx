<?php

class Group extends Eloquent {

    protected $softDelete = true;

    const STATUS_ACTIVE = "Active";
    const STATUS_INACTIVE = "Inactive";

    public function scopeActiveGroups($query){
        return $query->where('status', Group::STATUS_ACTIVE);
    }

    public function users(){
        return $this->hasMany('User', 'group_id');
    }

}