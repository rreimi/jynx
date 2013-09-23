<?php

class Job extends Eloquent{

    protected $fillable=array('company_name','state_id','city','job_title',
        'vacancy','job_type','temporary_months','description',
        'requirements','academic_level','experience_years',
        'age','sex','languages','salary','benefits',
        'contact_email','start_date','close_date','status');

    protected $softDelete = true;

    const TYPE_CONTRACTED='Contracted';
    const TYPE_TEMPORARY='Temporary';
    const TYPE_INTERNSHIP='Internship';
    const TYPE_INDEPENDENT='Independent';

    const ACADEMIC_LEVEL_SECONDARY='Secondary';
    const ACADEMIC_LEVEL_SENIOR_TECHNICIAN='Senior_Technician';
    const ACADEMIC_LEVEL_MASTER_SPECIALIZATION='Master_Specialization';
    const ACADEMIC_LEVEL_PHD='PhD';

    const SEX_MALE="Male";
    const SEX_FEMALE="Female";
    const SEX_INDISTINCT="Indistinct";

    const STATUS_DRAFT="Draft";
    const STATUS_PUBLISHED="Published";
    const STATUS_ON_HOLD="On_Hold";
    const STATUS_SUSPENDED="Suspended";


    public function areas(){
        return $this->belongsToMany('Area','jobs_areas');
    }

    public function careers(){
        return $this->belongsToMany('Career','jobs_careers');
    }
}