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

    }

    public static function getExperienceYears(){
        $years = array();
        for($i=1;$i<=6;$i++){
            $years[$i]=Lang::choice('content.year_experience',$i,array('number'=>$i==6?$i-1:$i));
        }
        return $years;
    }

    public static function getExperienceYear($id){
        if($id>=1 && $id<=6){
            return Lang::choice('content.year_experience',$id,array('number'=>$id==6?$id-1:$id));
        }else{
            return null;
        }
    }
}