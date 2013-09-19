<?php

class Career extends Eloquent{

    public function jobs(){
        return $this->belongsToMany('Job','jobs_careers');
    }

}