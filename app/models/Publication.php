<?php

class Publication extends Eloquent {

    protected $softDelete = true;

    const STATUS_DRAFT = "Draft";
    const STATUS_PUBLISHED = "Published";
    const STATUS_ON_HOLD = "On_Hold";
    const STATUS_SUSPENDED = "Suspended";

    protected $fillable = array('title', 'short_description',
        'long_description', 'status', 'from_date',
        'to_date', 'publisher_id', 'remember', 'latitude', 'longitude');

    public function scopeMostvisited($query, $pageSize){
        $query->orderBy('visits_number', 'desc')->with('publisher', 'images')->take($pageSize);
    }

    public function scopeRecent($query, $pageSize){
        $query->orderBy('created_at', 'desc')->with('publisher', 'images')->take($pageSize);
    }

    public function scopePublished($query){
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeParents($query) {
        return $query->where('category_id', '=', null);
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

    /**
     * Get the average for publication.
     * @param $query
     * @param $pubId
     */
    public function scopeCalculateRatingAvg($query, $pubId){
        $publication = Publication::find($pubId);

        if($publication){
            // Get average for the current publication
            $average = PublicationRating::where('publication_id', $pubId)->where('vote', '>', 0)->avg('vote');

            // Set average in the publication
            $publication->rating_avg = $average;
            $publication->save();
        }
    }

    /**
     * Suspend all publications by userId
     *
     * @param $query
     * @param $pubId
     */
    public function scopeSuspendPublicationsByUserId($query, $pubId){
        Publication::where('publisher_id', $pubId)->update(array('status'=>'Suspended'));
    }

    public function categories() {
        return $this->belongsToMany('Category', 'publications_categories');
    }

    public function contacts() {
        return $this->belongsToMany('Contact', 'publications_contacts');
    }

    public function images() {
        return $this->hasMany('PublicationImage');
    }

    public function ratings() {
        return $this->hasMany('PublicationRating');
    }

    public function publisher() {
        return $this->belongsTo('Publisher');
    }


    /* Method used to retrieve all publications next to expire (next 3 days) and send email notification. */
    public function scopeNextToExpirePublication($query){
        $dateReference = Date('Y-m-d', strtotime("+3 days"));

        return $query->where('to_date', $dateReference)
                     ->where('remember', true);
    }

}