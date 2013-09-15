@if (Auth::guest())
    <div id="modalReminder" class="modal hide fade">
        <div class="modal-header">
            <a href="#" class="close reminder-cancel">&times;</a>
            <h3>{{ Lang::get('content.reminder_header') }}</h3>
        </div>
        <div class="modal-body">
            <div class="reminder-question">
                <p>{{ Lang::get('content.reminder_question') }}</p>
                {{ Form::open(array('url' => 'login/olvido','class'=>'big-form', 'id' => 'reminderForm','method'=>'post')) }}
                {{ Form::email('reminder_email',null,array('placeholder' => Lang::get('content.email'),'class' => 'input-block-level required')) }}
                {{ Form::hidden('reminder_validation',URL::to('login/olvido-validar'),array('id'=>'reminderValidation')) }}
                {{ Form::close() }}
            </div>
            <div class="reminder-answer">
                <p>{{ Lang::get('content.reminder_answer') }}</p>
            </div>
        </div>
        <div class='modal-footer'>
            <button class="btn reminder-cancel">{{ Lang::get('content.cancel') }}</button>
            <button class="btn btn-primary reminder-send" data-loading-text="{{ Lang::get('content.sending') }}" data-complete-text="{{ Lang::get('content.finalize') }}">{{ Lang::get('content.send') }}</button>
        </div>
    </div>

    @section('scripts')
        @parent
        <script type="text/javascript">
            jQuery('.reminder-trigger').on('click', function () {
                Mercatino.reminderForm.show();
            });

            jQuery('.reminder-send').on('click',function(){
                Mercatino.reminderForm.send();
            });

            jQuery('.reminder-cancel').on('click',function(){
                Mercatino.reminderForm.hide();
            });
        </script>
    @stop
@endif