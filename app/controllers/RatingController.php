<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 11:06 PM
 */

class RatingController extends BaseController{

    public function __construct(){
        $this->beforeFilter('auth');
        $this->beforeFIlter('csrf-json', array('only' => array('postIndex')));
    }

    public function postIndex(){

        if (Request::ajax()) {

            $rules = array('vote' => 'required',
                'comment' => 'required',
                'user_id' => 'required',
                'publication_id' => 'required');

            $data = new stdClass;
            $data->comment = Input::get('report_comment');
            $data->vote = floatval(Input::get('rating-select'));
            $data->user_id = Auth::user()->id;
            $data->publication_id = intval(Input::get('rating_publication_id'));

            $validator = Validator::make((array) $data, $rules);

            $result = new stdClass;

            if($validator->fails()){

                $result->status = "error";
                $result->status_code = "validation";
                $result->errors = array();

                foreach ($validator->messages()->getMessages() as $msg) {
                    $result->errors[] =$msg[0];
                }

                return Response::json($result, 400);
            }

            $rating = new PublicationRating((array) $data);

            try {
                $rating->save();
            } catch(\Exception $exception) {
                $result->status = "error";
                $result->status_code = "validation";
                $result->errors = array(Lang::get('content.rating_publication_error'));
                return Response::json($result, 400);
            }

            $result->status = "success";
            $result->status_code = "rating_success";
            $result->message = Lang::get('content.rating_send_success');

            return Response::json($result, 200);

        } else {
            return Response::view('errors.missing', array(), 404);
        }

    }

    /**
     * @ajax
     * Retrieve reviews by publication_id
     * @param $publicationId = the publication id
     */
    public function postDenunciasPublicacion($publicationId, $offset = 0) {

        // Check valid publication
        $pub = Publication::find($publicationId);

        if (is_null($pub)){
            return Response::json('error_rating_invalid_pub', 404);
        }

        $totalRatings = PublicationRating::where('publication_id', $publicationId)->count();

        $ratingsList = PublicationRating::with('user')->ratingPageByPublication($publicationId, $offset)->get();

        $ratingsHtml = $this->getRatingBlock($ratingsList);

        $result = new stdClass;
        $result->totalRatings = $totalRatings;
        $result->ratings = $ratingsHtml;
        $result->pageSize = PublicationRating::$limitPagination;
//        if (sizeof($ratingsList) < PublicationRating::$limitPagination){
//            $result->limit = true;
//        }

        return Response::json($result, 200);

    }

    private function getRatingBlock($ratings){

        $html = '';

        foreach($ratings as $rating) {
            $html .= '<div class="rating-block">';

                $html .= 'vote = ' . $rating->vote .'<br/>';
                $html .= 'user = ' . $rating->user->full_name .'<br/>';
                $html .= 'fecha = ' . $rating->created_at .'<br/>';
                $html .= 'comentario = ' . $rating->comment .'<br/>';

            $html .= '</div>';
        }

        $html .= '<div class="clearfix"></div>';

        return $html;
    }

}