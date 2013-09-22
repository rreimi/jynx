<?php

class Area extends Eloquent{

    public function jobs(){
        return $this->belongsToMany('Job','jobs_areas');
    }

}