<div id="modal-register" class="modal modal-register hide fade">
    <div class="modal-header">
        <a href="javascript:Mercatino.registerForm.hide()" class="close">&times;</a>
        <h3>{{Lang::get('content.register_signup')}}</h3>
    </div>

    <div class="modal-body">
        @include('register_step1')
    </div>
    <div class='modal-footer'>
        <a href="javascript:Mercatino.registerForm.send()" class="btn btn-warning">{{Lang::get('content.register_signup')}}</a>
        <a href="javascript:Mercatino.registerForm.hide()" class="btn secondary">{{Lang::get('content.cancel')}}</a>
    </div>
</div>