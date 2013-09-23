<?php

class JobView extends Eloquent{

    protected $table = 'jobs_view';
    protected $softDelete = true;

    public function publisher() {
        return $this->belongsTo('Publisher');
    }
}