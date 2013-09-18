<div id="modal-register" class="modal modal-register hide fade">
    <div class="modal-header">
        <a nohref onclick="javascript:Mercatino.registerForm.hide()" class="close">&times;</a>
        <h3>{{Lang::get('content.register_signup')}}</h3>
    </div>

    <div class="modal-body">
        @include('register_step1')
    </div>
    <div class='modal-footer'>
        <a id="register_send_btn" class="btn btn-warning">{{Lang::get('content.register_signup')}}</a>
        <a id="register_cancel_btn" class="btn secondary">{{Lang::get('content.cancel')}}</a>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#register_send_btn').bind('click', function(){
            Mercatino.registerForm.send();
        });

        jQuery('#register_cancel_btn').bind('click', function(){
            Mercatino.registerForm.hide();
        });
    });
</script>
@stop