<div id="modal-terms" class="modal modal-terms hide fade">
    <div class="modal-header">
        <a nohref onclick="javascript:Mercatino.termsForm.hide()" class="close">&times;</a>
        <h2>TÉRMINOS Y CONDICIONES LEGALES</h2>
    </div>

    <div class="modal-body">
        @include('include.terms_and_conditions')
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    Mercatino.termsForm = {
        show: function(){
            jQuery('#modal-terms').modal('show');
        },
        hide: function(){
            jQuery('#modal-terms').modal('hide');
        }
    };
</script>
@stop