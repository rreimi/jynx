<?php

class ReportController extends BaseController {

    private $prefix = 'report';

    /**
     * @ajax
     *
     * @param $id the publication id
     * @return mixed json response
     */
    public function postCrear() {

        //Get publication data
        $repData = array(
            'user_id' => Auth::user()->id,
            'publication_id' => Request::get('publication_id'),
            'comment' => Request::get('comment'),
            'date' => date('Y-m-d H:i:s'),
            'status' => 'Pending',
        );

        //Set validation rules
        $rules = array(
            'user_id' => 'required',
            'publication_id' => 'required',
            'comment' => 'required',
            'date' => 'required',
            'status' => 'required',
        );

        // Validate fields
        $v = Validator::make($repData, $rules, array());
        if ( $v->fails() )
        {
            return Response::json(null, 400);
        }

        $rep = new PublicationReport($repData);
        $rep->save();

        if( $rep->id ) {
            return Response::json(null, 200);
        } else {
            return Response::json(null, 400);
        }
    }

    public function getDetalle($id){
        $response = PublicationReport::with('user', 'publication')->find($id);

        return View::make('include.report_view',
            array(
                'report'=> $response
            )
        );
    }

    public function postProcesar(){
        //Get report data
        $repData = array(
            'id' => Request::get('id'),
            'action' => Request::get('action'),
        );

        //Set validation rules
        $rules = array(
            'id' => 'required',
            'action' => 'required',
        );

        // Validate fields
        $v = Validator::make($repData, $rules, array());
        if ( $v->fails() || ($repData['action'] != 'valid-report' && $repData['action'] != 'invalid-report')){
            return Response::json('invalid_data', 400);
        }

        $rep = PublicationReport::find($repData['id']);

        if (empty($rep)){
            return Response::json('not_exist', 400);
        }

        if ($repData['action'] == 'valid-report'){
            $rep->status = PublicationReport::STATUS_CORRECT;
        } elseif ($repData['action'] == 'invalid-report'){
            $rep->status = PublicationReport::STATUS_INCORRECT;
        }

        $rep->save();

        self::addFlashMessage(null, Lang::get('content.report_message_change_success'), 'success');

        return Response::json('change_success', 200);

    }

}