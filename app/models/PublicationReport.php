<?php

class PublicationReport extends Eloquent {

    const STATUS_PENDING = "Pending";

    const STATUS_CORRECT = "Correct";

    const STATUS_INCORRECT = "Incorrect";

//    protected $softDelete = true;

    public $timestamps = false;

    protected $table = 'publications_reports';

    protected $fillable = array('user_id', 'publication_id',
                                'comment', 'date', 'status');

    public function scopePendingReports($query){
        $query->where('status', '=', 'Pending')
            ->orderBy('id', 'desc');
    }

    public function user(){
        return $this->belongsTo('User');
    }

    public function publication(){
        return $this->belongsTo('Publication');
    }

}