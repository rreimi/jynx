<div id="modal-rateit" class="modal modal-rateit hide fade">
    <div class="modal-header">
        <a nohref onclick="javascript:Mercatino.rateitForm.hide()" class="close">&times;</a>
        <h3>{{Lang::get('content.rate_publication')}}</h3>
    </div>

    <div class="modal-body">
        {{ Form::open(array('url' => 'evaluacion','class'=>'big-form register-form', 'id' => 'rating-form')) }}
        <span class="divider-vertical">{{Lang::get('content.rate_instructions')}}</span>
        <div class="rating-form rating-c">
            <select id="rating-sel" name="rating-select">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="divider-vertical">{{Lang::get('content.rate_title')}}</div>
        <input type="text" id="report_title_txt" name="title" class="required" max-length="80" />
        <div class="divider-vertical">{{Lang::get('content.rate_comment_instructions')}}</div>
        <textarea id="report_comment_txt" class="input-block-level" required name="report_comment" rows="8"></textarea>
        <input type="hidden" id="rating_publication_id" name="rating_publication_id" />
        {{ Form::close() }}
    </div>
    <div class='modal-footer'>
        <a nohref onclick="javascript:Mercatino.rateitForm.send()" class="btn btn-primary btn-small">{{Lang::get('content.rate_it')}}</a>
        <a nohref onclick="javascript:Mercatino.rateitForm.hide()" class="btn secondary">{{Lang::get('content.cancel')}}</a>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">

    Mercatino.rateitForm = {
        show: function(publicationId){
            //jQuery('#modal-confirm .modal-header h3').html(title);
            //jQuery('#modal-confirm .modal-body p').html(content);
            //jQuery('#modal-confirm .modal-footer a.danger').attr('href', url);
            jQuery('#rating-form').get(0).reset();
            jQuery('#modal-rateit').modal('show');
            jQuery('#rating-form #rating_publication_id').val(publicationId);
            jQuery('#rating-sel').barrating('clear');
        },
        hide: function(){
            jQuery('#modal-rateit').modal('hide')
        },
        send: function(){

            if (!jQuery('#rating-form').valid()){
                return false;
            }

            var formData = jQuery('#rating-form').serializeObject();
            var url = jQuery('#rating-form').attr('action');

            jQuery.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(result) {
                    Mercatino.rateitForm.hide();
                    Mercatino.showFlashMessage({title:'', message: result.message, type:'success'});
                    jQuery('#rating-form').get(0).reset();
                    Mercatino.ratings.currentPage = 0;
                    Mercatino.ratings.nextPage();
                },
                error: function(result) {
                    var data = result.responseJSON;
                    if (data.status_code == 'validation') {
                        for (var i = 0; i < data.errors.length; i++){
                            Mercatino.showFlashMessage({title:'', message: data.errors[i], type:'error'});
                        }
                        return false;
                    };

                    if (data.status_code == 'invalid_token') {
                        window.location.href = "/";
                    };
                }
            });


        },
        init: function(){
            jQuery('#rating-form').validateBootstrap({placement:'top'});
            jQuery('#rating-form #rating-sel').barrating({showValues:true, showSelectedRating:false});
        }
    };
</script>
@stop