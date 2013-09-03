<?php

class PublicationVisit extends Eloquent {

    protected $fillable = array('publication_id, created_at');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'publications_visits';

    public $timestamps = true;

    public function scopeMostVisited($query, $pageSize){
        $query->select('publication_id', DB::raw('COUNT(id) AS quantity'))
            ->where('created_at', '<', "DATE_SUB(NOW(), INTERVAL 7 day)")
            ->groupBy('publication_id')
            ->orderBy('quantity', 'desc')
            ->with('publication')
            ->take($pageSize);
    }

    public static function test(){
        $results = DB::select('SELECT * FROM publications_visits WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 day)');
        return $results;
    }

    public function publication(){
        return $this->belongsTo('Publication');
    }

}