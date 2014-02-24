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
                'comment' => 'max:300',
                'user_id' => 'required',
                'title' => 'max:80',
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

            // Validar que se haya enviado o la calificacion o el comentario
            if ($data->vote == 0 && $data->title == '' && $data->comment == ''){
                $result->status = "error";
                $result->status_code = "validation";
                $result->errors = array(Lang::get('content.rating_publication_empty_error'));

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

            // Enviar correo de notificación de comentario al anunciante
            $publication = Publication::find($data->publication_id);
            $publisher = User::find($publication->publisher->user_id);

            $receiver = array(
                'email' => $publisher->email,
                'name' => $publisher->full_name,
            );

            $data = array(
                'contentEmail' => 'publisher_new_comment',
                'publisherName' => $publisher->full_name,
                'userName' => Auth::user()->full_name,
                'publicationLink' => UrlHelper::toWith('publicacion/detalle/'. $data->publication_id, array()),
                'publicationTitle' => $publication->title,
                'ratingComment' => $data->comment,
            );

            $subject = Lang::get('content.email_publisher_new_comment');

            self::sendMail('emails.layout_email', $data, $receiver, $subject);

            // Calculate rating average for the publication related to this rating
            Queue::later(60, 'PublicationRatingAvg', $data->publication_id);

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
    public function postDenunciasPublicacion($publicationId, $pageNumber = 0) {

        // Check valid publication
        $pub = Publication::find($publicationId);

        if (is_null($pub)){
            return Response::json('error_rating_invalid_pub', 404);
        }

        $pageSize = PublicationRating::$limitPagination;
        $totalRatings = 0;
        if (Auth::check() && Auth::user()->isAdmin()){
            $totalRatings = PublicationRating::where('publication_id', $publicationId)->count();
        } else {
            $totalRatings = PublicationRating::where('publication_id', $publicationId)->where('status', 1)->count();
        }

        $offset = $pageSize * ($pageNumber-1);

        $qty = $pageNumber*PublicationRating::$limitPagination;
        $ratingsList = PublicationRating::with('user')->ratingPageByPublication($publicationId, $qty)->get();

        $ratingsHtml = $this->getRatingBlock($ratingsList, $totalRatings, $qty);

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

    /**
     * @ajax
     * Delete rating when current user is owner of rating
     * @param $ratingId
     */
    public function postDelete($ratingId) {

        // Check valid rating and belongs to the current user
        $rating = PublicationRating::find($ratingId);

        if (is_null($rating) || Auth::user()->id != $rating->user_id){
            return Response::json('error_invalid_rating', 404);
        }

        // It's valid, then delete it
        $rating->delete();

        return Response::json(null, 200);

    }

    private function getRatingBlock($ratings, $totalRatings, $qty){

        $html = '';

        if ($totalRatings == 0){
            $html .= '<div class="no-ratings">';
            $html .= Lang::get('content.rating_publication_no_items');
            $html .= '</div>';
        } else {
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
                if (Auth::check()){
                    $html .= '<div class="actions">';
                    if (Auth::user()->isAdmin()){
                        $html .= Lang::get('content.rating_status_admin_label');
                        $html .= '<div class="btn-group group-admin" data-toggle-id="'. $rating->id .'" data-toggle="buttons-radio" >';
                        if($rating->status){
                            $html .= '<button type="button" value="1" class="btn btn-small active" data-toggle="button">'. Lang::get('content.rating_status_on_admin_label') .'</button>
                                            <button type="button" value="0" class="btn btn-small" data-toggle="button">'. Lang::get('content.rating_status_off_admin_label') .'</button>';
                        } else {
                            $html .= '<button type="button" value="1" class="btn btn-small" data-toggle="button">'. Lang::get('content.rating_status_on_admin_label') .'</button>
                                            <button type="button" value="0" class="btn btn-small active" data-toggle="button">'. Lang::get('content.rating_status_off_admin_label') .'</button>';
                        }
                        $html .= '<input type="hidden" name="rating_hidden_'. $rating->id .'" value="'. $rating->status .'" />';
                        $html .= '</div>';
                    }
                    // If current user is owner of rating
                    if (Auth::user()->id == $rating->user_id){
                        $html .= Lang::get('content.rating_owner_label');
                        $html .= '<div class="btn-group group-owner">';
                        $html .= '<button type="button" data-id="'. $rating->id .'" class="btn btn-small">'. Lang::get('content.rating_owner_delete_label') .'</button>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
            }

            if($totalRatings > $qty){
                $html .= '
                        <div class="get-more">
                            <div id="get_more_preload" class="hide buttons-preload">
                                <img src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///zMzM87OzmdnZzMzM4GBgZqamqenpyH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==">
                            </div>
                            <button type="button" onclick="javascript:Mercatino.ratings.loadingState();Mercatino.ratings.nextPage();" class="btn btn-primary btn-small get-more-button">'. Lang::get('content.rating_get_more') .'</button>
                        </div>';
            }
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
        $operation = '';

        // Change status
        if ($status == 0){
            $rating->status = false;
            $message = Lang::get('content.rating_change_status_off');
            $operation = 'Inactive_rating';
        } else {
            $rating->status = true;
            $message = Lang::get('content.rating_change_status_on');
            $operation = 'Active_rating';
        }

        $resultSave = $rating->save();

        // Log when is changed the state of a rating by an admin
        Queue::push('LoggerJob@log', array('method' => null, 'operation' => $operation, 'entities' => array($rating),
            'userAdminId' => Auth::user()->id));

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