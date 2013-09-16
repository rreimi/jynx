<?php

class PublicationRating extends Eloquent {

    public $timestamps = true;
    protected $table = 'publications_ratings';
    protected $fillable = array('user_id', 'publication_id', 'comment', 'vote');

    public function publication(){
        return $this->belongsTo('Publication');
    }

}