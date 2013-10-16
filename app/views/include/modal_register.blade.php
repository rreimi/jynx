<div id="modal-register" class="modal modal-register hide fade">
    <div class="modal-header">
        <a nohref onclick="javascript:Mercatino.registerForm.hide()" class="close">&times;</a>
        <h3>{{Lang::get('content.register_signup')}}</h3>
    </div>

    <div class="modal-body">
        @include('register_step1')
    </div>
    <div class='modal-footer'>
        <div id="modal_register_preload" class="hide buttons-preload">
            <img src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///zMzM87OzmdnZzMzM4GBgZqamqenpyH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==">
            {{Lang::get('content.creating_account')}}
        </div>
        <div id="modal_register_buttons">
            <a id="register_send_btn" class="btn btn-primary">{{Lang::get('content.register_signup')}}</a>
            <a id="register_cancel_btn" class="btn secondary">{{Lang::get('content.cancel')}}</a>
        </div>
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


    Mercatino.registerForm = {
        show: function(title, content, url){
            //jQuery('#modal-confirm .modal-header h3').html(title);
            //jQuery('#modal-confirm .modal-body p').html(content);
            //jQuery('#modal-confirm .modal-footer a.danger').attr('href', url);
            jQuery('#register-form')[0].reset();
            jQuery('#modal-register').modal('show');
        },
        hide: function(){
            jQuery('#modal-register').modal('hide');
        },
        send: function(){

            if (!jQuery('#register-form').valid()){
                return false;
            }

            this.loadingState();

            var formData = jQuery('#register-form').serializeObject();
            var url = jQuery('#register-form').attr('action');

            jQuery.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                context: this,
                success: function(result) {
                    //var data = result.responseJSON;
                    window.location.href = result.redirect_url;
                },
                error: function(result) {
                    this.defaultState(); //Rob: this make reference to context object, defined on ajax call
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
        defaultState: function() {
            jQuery('#modal_register_buttons').show();
            jQuery('#modal_register_preload').hide();
        },
        loadingState: function() {
            jQuery('#modal_register_buttons').hide();
            jQuery('#modal_register_preload').show();
        },
        init: function () {
            jQuery('#register-form').validateBootstrap({
                placement:'bottom',
                rules: {
                    register_password: "required",
                    register_password_confirmation: {
                        equalTo: "#register_password"
                    }
                }
            });
        }
    };

</script>
@stop