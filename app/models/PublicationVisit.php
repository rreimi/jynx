<?php

class PublicationVisit extends Eloquent {

    protected $fillable = array('publication_id, date');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'publications_visits';

    public $timestamps = false;

    public function scopeMostVisited($query, $pageSize){
        $query->select('publication_id', DB::raw('COUNT(id) AS quantity'))
            ->where('date', '<', "DATE_SUB(NOW(), INTERVAL 7 day)")
            ->groupBy('publication_id')
            ->orderBy('quantity', 'desc')
            ->with('publication')
            ->take($pageSize);
    }

    public static function test(){
        $results = DB::select('SELECT * FROM publications_visits WHERE date > DATE_SUB(NOW(), INTERVAL 7 day)');
        return $results;
    }

    public function publication(){
        return $this->belongsTo('Publication');
    }

}