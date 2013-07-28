<div id="modal-report" class="modal modal-report hide fade">
    <div class="modal-header">
        <a href="javascript:Mercatino.reportForm.hide()" class="close">&times;</a>
        <h3>{{Lang::get('content.report_it')}}</h3>
    </div>

    <div class="modal-body">
        <span class="divider-vertical" style="margin-bottom:1em;">{{Lang::get('content.report_instructions')}}</span>
        <textarea id="report_comment_txt" class="input-block-level" name="report_comment" rows="8"></textarea>
    </div>
    <div class='modal-footer'>
        <a href="javascript:Mercatino.reportForm.send()" class="btn btn-warning">{{Lang::get('content.report_it')}}</a>
        <a href="javascript:Mercatino.reportForm.hide()" class="btn secondary">{{Lang::get('content.cancel')}}</a>
    </div>
</div>