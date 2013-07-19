<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Publication extends Eloquent {

    protected $softDelete = true;

    protected $fillable = array('title', 'short_description',
        'long_description', 'status', 'from_date',
        'to_date', 'publisher_id', 'remember');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	//protected $table = 'categories';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

    public function scopeMostvisited($query){
        return $query->orderBy('visits_number', 'desc')->take(4);
    }

    public function scopePublished($query){
        return $query->where('status', 'Published');
    }

    public function scopeParents($query) {
        return $query->where('category_id', '=', null);
    }

    public function categories() {
        return $this->belongsToMany('Category', 'publications_categories');
    }

    public function images() {
        return $this->hasMany('PublicationImage');
    }


    public function publisher() {
        return $this->belongsTo('Publisher');
    }

    public static function getSearch($q) {
        /*
         (SELECT p.*
            FROM  `publications` AS p
            JOIN category_publication AS cp ON p.id = cp.publication_id
            WHERE cp.category_id
            IN ( SELECT id FROM categories WHERE name LIKE  '%asdasadsdas%')
            OR p.long_description LIKE '%carton%'

            ) UNION (
            SELECT p.*
            FROM  `publications` AS p
            JOIN publishers AS u ON p.publisher_id = u.id
            WHERE u.seller_name LIKE  '%pepeasd%')
         */

        $cats = Category::where('name', 'like', "%$q%")->lists('id');
        if (empty($cats)) ($cats[] = 0);

        return Publication::select('*')
//            ->leftjoin('publications_categories', 'publications.id', '=', 'publications_categories.publication_id')
//            ->whereIn('publications_categories.category_id', $cats)
            ->orWhere('title', 'like', "%$q%")
            ->orWhere('short_description', 'like', "%$q%")
            ->orWhere('long_description', 'like', "%$q%")
            ->orWhere(function($query) use ($q) {
                    $publishers = Publisher::where('seller_name', 'like', "%$q%")->lists('id');
                    if (empty($publishers)) ($publishers[] = 0);
                    $query->whereIn('publications.publisher_id', $publishers);
        });
    }

}