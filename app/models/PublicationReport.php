<?php

class PublicationReport extends Eloquent {

    const STATUS_PENDING = "Pending";
    const STATUS_INVALID = "Invalid";
    const STATUS_VALID = "Valid";
    const STATUS_DELETED_COMMENT = "DeletedComment";
    const STATUS_SUSPENDED_PUBLICATION = "SuspendedPublication";
    const STATUS_SUSPENDED_PUBLISHER = "SuspendedPublisher";
    const STATUS_SUSPENDED_REPORTER = "SuspendedReporter";

//    protected $softDelete = true;

    public $timestamps = false;

    protected $table = 'publications_reports';

    protected $fillable = array('user_id', 'publication_id',
                                'comment', 'date', 'status');

    public function scopePendingReports($query){
        $query->where('status','Pending')
            ->orderBy('id', 'desc');
    }

    public function user(){
        return $this->belongsTo('User');
    }

    public function publication(){
        return $this->belongsTo('Publication');
    }

}