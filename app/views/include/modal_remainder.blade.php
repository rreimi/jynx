@if (Auth::guest())
    <div id="modalRemainder" class="modal hide fade">
        <div class="modal-header">
            <a href="#" class="close remainder-cancel">&times;</a>
            <h3>Te ayudaré</h3>
        </div>
        <div class="modal-body">
            <p>¿ Cual es tu correo ?</p>
            {{ Form::open(array('url' => 'olvido','class'=>'big-form', 'id' => 'remainderForm','method'=>'post')) }}
            {{ Form::email('remainder_email',null,array('placeholder' => Lang::get('content.email'),'class' => 'input-block-level required')) }}
            {{ Form::close() }}
        </div>
        <div class='modal-footer'>
            <a href="#" class="btn remainder-cancel">{{ Lang::get('content.cancel') }}</a>
            <a href="#" class="btn btn-primary remainder-send">{{ Lang::get('content.send') }}</a>
        </div>
    </div>

    @section('scripts')
        @parent
        <script type="text/javascript">
            jQuery('.remainder-trigger').on('click', function () {
                Mercatino.remainderForm.show();
            });

            jQuery('.remainder-send').on('click',function(){
                Mercatino.remainderForm.send();
            });

            jQuery('.remainder-cancel').on('click',function(){
                Mercatino.remainderForm.hide();
            });
        </script>
    @stop
@endif