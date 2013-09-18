<?php

class PublicationRating extends Eloquent {

    public $timestamps = true;
    protected $table = 'publications_ratings';
    protected $fillable = array('user_id', 'publication_id', 'comment', 'vote');
    public static $limitPagination = 5;

    public function scopeRatingPageByPublication($query, $publicationId, $offset = 0){
        $query->where('publication_id', '=', $publicationId)
            ->take(self::$limitPagination)
            ->skip($offset);
    }

    public function publication(){
        return $this->belongsTo('Publication');
    }

    public function user(){
        return $this->belongsTo('User');
    }

}