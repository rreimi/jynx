<?php

class PublicationRating extends Eloquent {

    protected $softDelete = true;
    public $timestamps = true;
    protected $table = 'publications_ratings';
    protected $fillable = array('user_id', 'publication_id', 'comment', 'vote', 'title');
    public static $limitPagination = 5;

    public function scopeRatingPageByPublication($query, $publicationId, $qty = 1){
        $query->where('publication_id', '=', $publicationId);

        // Add filter by status active when the user isn't an admin
        if (!(Auth::check() && Auth::user()->isAdmin())){
            $query->where('status', '=', true);
        }

        $query->orderBy('id', 'desc')
              ->take($qty);
//              ->skip($offset);
    }

    public function publication(){
        return $this->belongsTo('Publication');
    }

    public function user(){
        return $this->belongsTo('User');
    }

}