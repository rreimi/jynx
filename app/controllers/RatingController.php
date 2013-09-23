<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 11:06 PM
 */

class RatingController extends BaseController{

    protected $fillable = array('title', 'comment', 'vote', 'status');

    public function __construct(){
        $this->beforeFilter('auth', array('except'=>array('postDenunciasPublicacion')));
        $this->beforeFIlter('csrf-json', array('only' => array('postIndex')));
    }

    public function postIndex(){

        if (Request::ajax()) {

            $rules = array('vote' => 'required',
                'comment' => 'required|max:300',
                'user_id' => 'required',
                'title' => 'required|max:80',
                'publication_id' => 'required');

            $data = new stdClass;
            $data->title = Input::get('title');
            $data->comment = Input::get('report_comment');
            $data->vote = intval(Input::get('rating-select'));
            $data->user_id = Auth::user()->id;
            $data->publication_id = intval(Input::get('rating_publication_id'));

            $messages = array(
                'comment.max' => 'Los comentarios deben tener una logitud máxima de 300 caracteres');

            $validator = Validator::make((array) $data, $rules, $messages);

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
    public function postDenunciasPublicacion($publicationId, $pageNumber = 1) {

        // Check valid publication
        $pub = Publication::find($publicationId);

        if (is_null($pub)){
            return Response::json('error_rating_invalid_pub', 404);
        }

        $pageSize = PublicationRating::$limitPagination;
        $totalRatings = PublicationRating::where('publication_id', $publicationId)->count();

        $offset = $pageSize * ($pageNumber-1);

        $ratingsList = PublicationRating::with('user')->ratingPageByPublication($publicationId, $offset)->get();

        $ratingsHtml = $this->getRatingBlock($ratingsList);

        $result = new stdClass;
        $result->totalRatings = $totalRatings;
        $result->ratings = $ratingsHtml;
        $result->pageSize = $pageSize;
        if ($pageNumber == 1){
            $result->limit = 'top';
        } elseif ($pageNumber >= ($totalRatings/$pageSize)){
            $result->limit = 'bottom';
        }

        return Response::json($result, 200);

    }

    private function getRatingBlock($ratings){

        $html = '';

        foreach($ratings as $rating) {
            $html .= '<div class="rating-block">';
                $html .= '<div class="head">';
                        $html .= RatingHelper::getRatingBar($rating->vote);
                        $html .= '<div class="info">';
                            $html .= '<span class="nickname">' . $rating->user->full_name .'</span>';
                            $originalDate = $rating->created_at;
                            $newDate = date("d-m-Y", strtotime($originalDate));
                            $html .= '<span class="date">' . $newDate .'</span>';
                            $html .= '<span class="title-rating">' . $rating->title .'</span>';
                        $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="description">';
                    $html .= $rating->comment;
                $html .= '</div>';
                if (Auth::check() && Auth::user()->isAdmin()){
                    $html .= '<div class="admin">';
                        $html .= Lang::get('content.rating_status_admin_label') .":" ;
                        $html .= '<div class="btn-group" data-toggle-id="'. $rating->id .'" data-toggle="buttons-radio" >';
                            if($rating->status){
                                $html .= '<button type="button" value="1" class="btn btn-small active" data-toggle="button">'. Lang::get('content.rating_status_on_admin_label') .'</button>
                                        <button type="button" value="0" class="btn btn-small" data-toggle="button">'. Lang::get('content.rating_status_off_admin_label') .'</button>';
                            } else {
                                $html .= '<button type="button" value="1" class="btn btn-small" data-toggle="button">'. Lang::get('content.rating_status_on_admin_label') .'</button>
                                        <button type="button" value="0" class="btn btn-small active" data-toggle="button">'. Lang::get('content.rating_status_off_admin_label') .'</button>';
                            }
                            $html .= '<input type="hidden" name="rating_hidden_'. $rating->id .'" value="'. $rating->status .'" />';
                        $html .= '</div>';
                    $html .= '</div>';
                }
            $html .= '</div>';
        }

        if (sizeof($ratings) == 0){
            $html .= '<div class="no-ratings">';
            $html .= Lang::get('content.rating_publication_no_items');
            if (!Auth::check()){
                $html .= Lang::get('content.rating_publication_first_rating', array('loginUrl' => URL::to('login')));
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="pagination">
                            <ul>
                                <li class="top-page"><a href="javascript:Mercatino.ratings.previousPage()"><<</a></li>
                                <li class="top-page"><a class="previous-page" href="javascript:Mercatino.ratings.previousPage()"></a></li>
                                <li><a class="current-page" nohref></a></li>
                                <li class="bottom-page"><a class="next-page" href="javascript:Mercatino.ratings.nextPage()"></a></li>
                                <li class="bottom-page"><a href="javascript:Mercatino.ratings.nextPage()">>></a></li>
                            </ul>
                        </div>';
        }

        return $html;
    }

    /**
     * @ajax
     * Change the status of ratings
     * @param $publicationId = the publication id
     */
    public function postCambiarEstatus($ratingId, $status) {

        // Check valid rating
        $rating = PublicationRating::find($ratingId);

        if (is_null($rating)){
            return Response::json('error_rating_invalid', 404);
        }

        if (is_null($status)){
            return Response::json('error_rating_invalid_status', 404);
        }

        $message = '';

        // Change status
        if ($status == 0){
            $rating->status = false;
            $message = Lang::get('content.rating_change_status_off');
        } else {
            $rating->status = true;
            $message = Lang::get('content.rating_change_status_on');
        }

        $resultSave = $rating->save();

        $result = new stdClass;

        if ($resultSave){
            $result->result = 'success';
            $result->message = $message;
        } else {
            $result->result = 'error';
        }

        return Response::json($result, 200);

    }

}