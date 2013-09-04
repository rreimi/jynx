<?php

class State extends Eloquent {

    public function publishers(){
        return $this->hasMany('Publisher','state_id');
    }
}