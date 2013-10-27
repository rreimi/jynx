@if (Auth::guest())
    <div id="modalReset" class="modal hide fade">
        <div class="modal-header">
            <h3>{{ Lang::get('content.reset_header') }}</h3>
        </div>
        <div class="modal-body">
            <div class="reset-question">
                <p>{{ Lang::get('content.reset_question') }}</p>
                {{ Form::open(array('url' => 'login/restaurar','class'=>'big-form', 'id' => 'resetForm','method'=>'post')) }}
                <h2 class="email"> </h2>
                {{ Form::password('reset_password',array('placeholder' => Lang::get('content.password'),'class' => 'input-block-level required')) }}
                {{ Form::password('reset_password_confirmation',array('placeholder' => Lang::get('content.password_confirmation'),'class' => 'input-block-level required')) }}
                {{ Form::hidden('reset_email','',array('id'=>'resetEmail')) }}
                {{ Form::hidden('reset_token','',array('id'=>'resetValidation')) }}
                {{ Form::close() }}
            </div>
            <div class="reset-answer">
                <p>{{ Lang::get('content.reset_answer') }}</p>
            </div>
        </div>
        <div class='modal-footer'>
            <button class="btn btn-primary reset-send" data-loading-text="{{ Lang::get('content.sending') }}" data-complete-text="{{ Lang::get('content.finalize') }}">{{ Lang::get('content.send') }}</button>
        </div>
    </div>

    @section('scripts')
        @parent
        <script type="text/javascript">
            jQuery('.reset-send').on('click',function(){
                Mercatino.resetForm.send();
            });
        </script>
    @stop
@endif