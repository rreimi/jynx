<?php
/**
 * User: JGAB
 * Timestamp: 26/10/13 05:06 PM
 */

class DateHelper {

    public static function getMonths(){
        $months = array();
        for($i=1;$i<=12;$i++){
            $months[$i]=Lang::choice('content.month',$i,array('number'=>$i));
        }
        return $months;
    }

    public static function getMonth($id){
        if($id>=1 && $id<=12){
            return Lang::choice('content.month',$id,array('number'=>$id));
        }else{
            return null;
        }
    }

    public static function getYears(){

    }
}