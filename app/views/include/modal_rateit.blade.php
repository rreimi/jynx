<div id="modal-rateit" class="modal modal-rateit hide fade">
    <div class="modal-header">
        <a href="javascript:Mercatino.rateitForm.hide()" class="close">&times;</a>
        <h3>{{Lang::get('content.rate_publication')}}</h3>
    </div>

    <div class="modal-body">
        {{ Form::open(array('url' => 'evaluacion','class'=>'big-form register-form', 'id' => 'rating-form')) }}
        <span class="divider-vertical" style="margin-bottom:1em;">{{Lang::get('content.rate_instructions')}}</span>
        <div class="rating-form rating-c ">
            <select id="rating-sel" name="rating-select">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <span class="divider-vertical" style="margin-bottom:1em;">{{Lang::get('content.rate_comment_instructions')}}</span>
        <textarea id="report_comment_txt" class="input-block-level" name="report_comment" rows="8"></textarea>
        <input type="hidden" name="rating_publication_id" />
        {{ Form::close() }}
    </div>
    <div class='modal-footer'>
        <a href="javascript:Mercatino.rateitForm.send()" class="btn btn-primary btn-small">{{Lang::get('content.rate_it')}}</a>
        <a href="javascript:Mercatino.rateitForm.hide()" class="btn secondary">{{Lang::get('content.cancel')}}</a>
    </div>
</div>