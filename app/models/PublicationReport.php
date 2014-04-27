<?php

class PublicationReport extends Eloquent {

    const STATUS_PENDING = "Pending";
    const STATUS_INVALID = "Invalid";
    const STATUS_VALID = "Valid";

    const STATE_VALID_OR_ACTION = "ValidOrAction";

//    protected $softDelete = true;

    public $timestamps = false;

    protected $table = 'publications_reports';

    protected $fillable = array('user_id', 'publication_id',
                                'comment', 'date', 'status');

    public function scopePendingReports($query){
        $query->where('publications_reports.status', self::STATUS_PENDING);

        // Filter by subAdmin group
        if (Auth::user()->isSubAdmin()){
            $query->leftJoin('users','users.id','=','publications_reports.user_id');
            $query->where('users.group_id', Auth::user()->group_id);
        }

        $query->orderBy('publications_reports.id', 'desc');
    }

    public function scopeValidOrActionReports($query){
        if (Auth::user()->isSubAdmin()){
            $query->join('publications_view', 'publications_view.id', '=', 'publications_reports.publication_id')
                ->join('publishers', 'publishers.id', '=', 'publications_view.publisher_id')
                ->join('users', 'users.id', '=', 'publishers.user_id')
                ->where('users.group_id', Auth::user()->group_id);
        }

        $query->where('publications_reports.status','<>', self::STATUS_PENDING)
            ->where('publications_reports.status','<>', self::STATUS_INVALID)
            ->orderBy('publications_reports.id', 'desc');
    }

    public function scopeReportersWithReports($query) {
        $query->select('user_id', 'full_name')
              ->join('users','users.id','=','publications_reports.user_id')
              ->groupBy('user_id')
              ->orderBy('full_name');
    }

    public function scopePublicationsWithReports($query) {
        $query->select('publication_id', 'title')
            ->join('publications_view','publications_view.id','=','publications_reports.publication_id')
            ->groupBy('publication_id')
            ->orderBy('title');
    }

    public function scopePublishersWithReports($query) {
        $query->select('publisher_id', 'seller_name')
            ->join('publications_view','publications_view.id','=','publications_reports.publication_id')
            ->groupBy('publisher_id')
            ->orderBy('title');
    }

    public function scopeAllRows($query){
        if (Auth::user()->isSubAdmin()){
            $query->join('publications_view', 'publications_view.id', '=', 'publications_reports.publication_id')
                ->join('publishers', 'publishers.id', '=', 'publications_view.publisher_id')
                ->join('users', 'users.id', '=', 'publishers.user_id')
                ->where('users.group_id', Auth::user()->group_id);
        }
    }

    public function user(){
        return $this->belongsTo('User');
    }

    public function publication(){
        return $this->belongsTo('Publication');
    }

}