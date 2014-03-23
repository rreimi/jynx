<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Publisher extends Eloquent {

    protected $softDelete = true;

    const STATUS_PENDING="Pending";

    const STATUS_APPROVED="Approved";

    const STATUS_DENIED="Denied";

    const STATUS_SUSPENDED="Suspended";

    protected $fillable = array('publisher_type', 'seller_name',
        'letter_rif_ci', 'rif_ci', 'state_id', 'city',
        'media', 'phone1', 'phone2');

    public function scopeStatusApproved($query){
        $query->where('status_publisher', '=', self::STATUS_APPROVED);
    }

    public function scopeWithPublications($query){
        $query->select('publishers.id', 'publications.id', 'publications.publisher_id')
            ->join('publications','publishers.id','=','publications.publisher_id')
            ->groupBy('publications.publisher_id');
    }

    public function sectors() {
        return $this->belongsToMany('BusinessSector', 'publishers_sectors');
    }

    public function contacts() {
        return $this->hasMany('Contact');
    }

    public function state() {
        return $this->belongsTo('State');
    }

    public function categories(){
        return $this->belongsToMany('Category','publishers_categories');
    }

    public function user(){
        return $this->belongsTo('User');
    }

    public function getMainContact(){
        return Contact::where('publisher_id', $this->id)->where('is_main', '')->first();
    }
    //after save create contact :D

}