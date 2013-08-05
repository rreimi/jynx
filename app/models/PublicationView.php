<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class PublicationView extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'publications_view';
    protected $softDeletes = true;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

    public function scopePublished($query){
        return $query->where('status', 'Published');
    }

    public function scopeFilter($query, $filters) {
        if (isset($filters['state'])){
            $query->where('state_id', $filters['state']->id);
        }

        if (isset($filters['seller'])){
            $query->where('publisher_id', $filters['seller']->id);
        }

        if (isset($filters['category'])){
            $query->where('category_id', $filters['category']->id);
        }

        return $query;
    }

    public function scopePublishersWithPublications($query) {
        $query->select('publisher_id', 'seller_name', DB::raw('count(*) as publications_number'))->groupBy('publisher_id');
    }

    public function scopeCategoriesWithPublications($query) {
        $query->select('category_id', 'category_name', DB::raw('count(*) as publications_number'))->groupBy('category_id');
    }


    public function scopeParents($query) {
        return $query->where('category_id', '=', null);
    }

    public function categories() {
        return $this->belongsToMany('Category', 'publications_categories');
    }

    public function images() {
        return $this->hasMany('PublicationImage', 'publication_id');
    }


    public function publisher() {
        return $this->belongsTo('Publisher');
    }

    public static function getSearch($q, $orderBy = 'visits_number', $orderDir = 'desc') {
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
        $query = PublicationView::orderBy($orderBy, $orderDir);

        $tokens = explode(' ', $q);

        foreach ($tokens as $token) {


            $query->Where(function($query) use ($token)
            {
                $query->orWhere('title', 'like', "%$token%")
                    ->orWhere('short_description', 'like', "%$token%")
                    ->orWhere('long_description', 'like', "%$token%")
                    ->orWhere('seller_name', 'like', "%$token%")
                    ->orWhere('category_name', 'like', "%$token%")
                    ->orWhere('city', 'like', "%$token%")
                    ->orWhere('state', 'like', "%$token%")
                    ->orWhere('contacts', 'like', "%$token%");
            });

//            $query->orWhere('title', 'like', "%$token%")
//                ->orWhere('short_description', 'like', "%$token%")
//                ->orWhere('long_description', 'like', "%$token%")
//                ->orWhere('seller_name', 'like', "%$token%")
//                ->orWhere('categories_name', 'like', "%$token%")
//                ->orWhere('city', 'like', "%$token%")
//                ->orWhere('state', 'like', "%$token%");
        }

        return $query;

//        return PublicationView::select('*')
////            ->leftjoin('publications_categories', 'publications.id', '=', 'publications_categories.publication_id')
////            ->whereIn('publications_categories.category_id', $cats)
//            ->orWhere('title', 'like', "%$q%")
//            ->orWhere('short_description', 'like', "%$q%")
//            ->orWhere('long_description', 'like', "%$q%")
//            ->orWhere('seller_name', 'like', "%$q%")
//            ->orWhere('categories_name', 'like', "%$q%")
//            ->orWhere('city', 'like', "%$q%")
//            ->orWhere('state', 'like', "%$q%")
//            ;

    }

}