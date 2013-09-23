<?php

class JobController extends BaseController {

    private $pageSize = '6';

    public function __construct(){
        $this->beforeFilter('auth',['only'=>['getDetalle','getLista']]);
    }

    public function getIndex(){
        return $this->jobList(true);
    }

    public function postIndex(){
        return $this->getIndex();
    }

    public function getLista(){
        return $this->jobList(false);
    }

    public function postLista(){
        return $this->getLista();
    }

    private function jobList($all){
        $state = self::retrieveListState();

        $jobs=JobView::select('jobs_view.*')
            ->orderBy($state['sort'], $state['order']);

        if(Auth::user()){
            $jobs->with('publisher');
        }

        $q = $state['q'];

        if (!empty($q)){
            $jobs->where(function($query) use ($q)
            {
                $query->orWhere('company_name', 'LIKE', '%' . $q . '%')
                    ->orWhere('job_title', 'LIKE', '%' . $q . '%')
                    ->orWhere('description', 'LIKE', '%' . $q . '%')
                    ->orWhere('requirements', 'LIKE', '%' . $q . '%')
                    ->orWhere('age', 'LIKE', '%' . $q . '%')
                    ->orWhere('benefits', 'LIKE', '%' . $q . '%')
                    ->orWhere('contact_email', 'LIKE', '%' . $q . '%')
                    ->orWhere('location', 'LIKE', '%' . $q . '%')
                    ->orWhere('areas', 'LIKE', '%' . $q . '%')
                    ->orWhere('careers', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        if (!empty($state['filter_state'])){
            $jobs->where('state_id', '=', $state['filter_state']);
        }

        if (is_array($state['filter_areas'])) {
            $jobs
                ->join('jobs_areas','jobs_view.id','=','jobs_areas.job_id')
                ->whereIn('jobs_areas.area_id', $state['filter_areas']);
        }

        if (!empty($state['from_job_date'])) {
            $jobs->where('start_date', '>=', date("Y-m-d", strtotime($state['from_job_date'])));
        }

        if (!empty($state['to_job_date'])) {
            $jobs->where('close_date', '<=', date("Y-m-d", strtotime($state['to_job_date'])));
        }

        if(!$all){
            $jobs->where('publisher_id', '=', Auth::user()->publisher->id);
        }

        $jobs=$jobs->paginate($this->pageSize);

        return View::make('job_list',[
            'jobs'=>$jobs,
            'state' => $state,
            'all'=>$all,
            'states'=>[''=>Lang::get('content.select_state')]+State::lists('name','id'),
            'areas'=>Area::lists('name','id')
        ]);
    }

    public function getDetalle($id = null) {

        if (null == $id){
            return Response::view('errors.missing', array(), 404);
        }

        $job=JobView::find($id);

        if(null == $job){
            return Response::view('errors.missing', array(), 404);
        }

        $companyPicture=Publisher::find($job->publisher_id)->avatar;

        return Response::view('job',[
            'job'=>$job,
            'companyPicture'=>$companyPicture
        ]);
    }


    private $jobListSort = array('job_title', 'location', 'areas', 'start_date', 'close_date', 'status');

    private function retrieveListState(){
        $state = Session::get('job_list.state');
        $isPost = (Input::server("REQUEST_METHOD") == "POST");

        $state['active_filters'] = is_null($state['active_filters'])? 0 : $state['active_filters'];

        $sort = (in_array(Input::get('sort'), $this->jobListSort) ? Input::get('sort') : null);

        if ((isset($sort)) || !(isset($state['sort']))) {
            $state['sort'] = (isset($sort))? $sort : 'id';
        }

        $order = (in_array(Input::get('order'), array('asc', 'desc')) ? Input::get('order') : null);

        if ((isset($order)) || !(isset($state['order']))) {
            $state['order'] = (isset($order))? $order : 'desc';
        }

        $q = (!is_null(Input::get('q')) ? Input::get('q') : null);

        if ((isset($q)) || !(isset($state['q']))) {
            $state['q'] = (isset($q))? $q : '';
        }

        $filterState = (!is_null(Input::get('filter_state')) ? Input::get('filter_state') : null);

        if ((isset($filterState)) || !(isset($state['filter_state']))) {
            $state['filter_state'] = (isset($filterState))? $filterState : '';
        }

        $state['filter_areas'] = (isset($state['filter_areas']) ? $state['filter_areas'] : null);

        if ($isPost) {
            $state['filter_areas'] = Input::get('filter_areas');
        }

        $state['from_job_date'] = (isset($state['from_job_date']) ? $state['from_job_date'] : null);

        if ($isPost) {
            $state['from_job_date'] = Input::get('from_job_date');
        }

        //To start date
        $state['to_job_date'] = (isset($state['to_job_date']) ? $state['to_job_date'] : null);

        if ($isPost) {
            $state['to_job_date'] = Input::get('to_job_date');
        }


        $ignoreFilters = array('active_filters', 'sort', 'order');
        if ($isPost) {
            $state['active_filters'] = 0;
            foreach ($state as $key => $item) {
                if (!in_array($key, $ignoreFilters)) {
                    if (isset($item) && !empty($item)){
                        $state['active_filters']++;
                    }
                }
            }
        }

        Session::put('job_list.state', $state);

        return $state;
    }

    public function getCrear(){

        $job=new Job();
        $job->areas=Input::old('area_ids');
        $job->careers=Input::old('career_ids');

        return View::make('job_form',[
            'companyName'=>Auth::user()->publisher->seller_name,
            'areas'=>Area::lists('name','id'),
            'careers'=>Career::lists('name','id'),
            'avatar'=>Auth::user()->publisher->avatar,
            'states'=>[''=>Lang::get('content.select_state')]+State::lists('name','id'),
            'jobTypes'=>[
                ''=>Lang::get('content.select_default'),
                Job::TYPE_CONTRACTED => Lang::get('content.job_type_contracted'),
                Job::TYPE_INDEPENDENT => Lang::get('content.job_type_independent'),
                Job::TYPE_INTERNSHIP => Lang::get('content.job_type_internship'),
                Job::TYPE_TEMPORARY => Lang::get('content.job_type_temporary'),
            ],
            'vacancies'=>[''=>Lang::get('content.select_default'),1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10],
            'academicLevels'=>[
                ''=>Lang::get('content.select_default'),
                Job::ACADEMIC_LEVEL_SECONDARY => Lang::get('content.job_academic_level_secondary'),
                Job::ACADEMIC_LEVEL_SENIOR_TECHNICIAN => Lang::get('content.job_academic_level_senior_technician'),
                Job::ACADEMIC_LEVEL_MASTER_SPECIALIZATION => Lang::get('content.job_academic_level_master_specialization'),
                Job::ACADEMIC_LEVEL_PHD => Lang::get('content.job_academic_level_phd')
            ],
            'sexes'=>[
                ''=>Lang::get('content.select_default'),
                Job::SEX_MALE=>Lang::get('content.male'),
                Job::SEX_FEMALE=>Lang::get('content.female'),
                Job::SEX_INDISTINCT=>Lang::get('content.indistinct')
            ],
            'statuses' =>[
                ''=>Lang::get('content.select_default'),
                Job::STATUS_DRAFT => Lang::get('content.status_publication_Draft'),
                Job::STATUS_PUBLISHED => Lang::get('content.status_publication_Published'),
                Job::STATUS_ON_HOLD => Lang::get('content.status_publication_On_Hold'),
                Job::STATUS_SUSPENDED => Lang::get('content.status_publication_Suspended'),
            ],
            'referer' => URL::previous(),
            'job'=>$job
        ]);
    }

    public function postGuardar(){

        //TODO agregar manejo para guardar la edicion

        $jobData=[
            'company_name'=>Input::get('company_name'),
            'state_id'=>Input::get('state_id'),
            'city'=>Input::get('city'),
            'job_title'=>Input::get('job_title'),
            'vacancy'=>Input::get('vacancy'),
            'job_type'=>Input::get('job_type'),
            'temporary_months'=>Input::get('temporary_months'),
            'area_ids'=>Input::get('area_ids'),
            'description'=>Input::get('description'),
            'requirements'=>Input::get('requirements'),
            'academic_level'=>Input::get('academic_level'),
            'career_ids'=>Input::get('career_ids'),
            'experience_years'=>Input::get('experience_years'),
            'age'=>Input::get('age'),
            'sex'=>Input::get('sex'),
            'languages'=>Input::get('languages'),
            'salary'=>Input::get('salary'),
            'benefits'=>Input::get('benefits'),
            'contact_email'=>Input::get('contact_email'),
            'start_date'=>Input::get('start_date'),
            'close_date'=>Input::get('close_date'),
            'status'=>Input::get('status')
        ];

        $rules=[
            'company_name'=>'required',
            'job_title'=>'required',
            'description'=>'required',
            'state_id'=>'required',
            'area_ids'=>'required',
            'contact_email'=>'required',
            'status'=>'required'
        ];

        $validator=Validator::make($jobData,$rules);

        if($validator->fails()){
            return Redirect::to('bolsa-trabajo/crear')
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function() use($jobData){

            $job= new Job($jobData);

            $job->publisher_id=Auth::user()->publisher->id;

            $job->start_date = $jobData['start_date']!=null?date('Y-m-d',strtotime($jobData['start_date'])):null;
            $job->close_date = $jobData['close_date']!=null?date('Y-m-d',strtotime($jobData['close_date'])):null;

            $job->save();

            $job->areas()->sync((array)$jobData['area_ids']);

            $job->careers()->sync((array)$jobData['career_ids']);
        });

        return Redirect::to('bolsa-trabajo/lista');
    }

}