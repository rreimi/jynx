jQuery.fn.serializeObject = function() {
    var arrayData, objectData;
    arrayData = this.serializeArray();
    objectData = {};

    $.each(arrayData, function() {
        var value;

        if (this.value != null) {
            value = this.value;
        } else {
            value = '';
        }

        if (objectData[this.name] != null) {
            if (!objectData[this.name].push) {
                objectData[this.name] = [objectData[this.name]];
            }

            objectData[this.name].push(value);
        } else {
            objectData[this.name] = value;
        }
    });

    return objectData;
};

$.pnotify.defaults.history = false;

$.pnotify.defaults.delay = 3000;

if (jQuery) {
    // Numeric only control handler
    jQuery.fn.numericField = function() {
        return this.each(function()
        {
            $(this).keydown(function(e)
            {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, arrows, numbers
                // and keypad numbers ONLY
                return (
                    key == 8 ||
                        key == 9 ||
                        key == 46 ||
                        (key >= 37 && key <= 40) ||
                        (key >= 48 && key <= 57) ||
                        (key >= 96 && key <= 105));
            });
        });
    };// JavaScript Document

    jQuery.fn.validateBootstrap = function(opts){
        var options = $.extend({}, {
            boxErrorClass: 'alert-error',
            inputErrorClass: 'error',
            placement: 'right',
            messages: {},
//            focusInvalid: true,
//            focusCleanup:true,
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            errorPlacement: function(error, element) {
                jQuery(element).parent().addClass(this.inputErrorClass);
                jQuery(element).popover('destroy');
                jQuery(element).popover(
                    {
                        content:error.text(),
                        placement:jQuery(element).data('placement') || this.placement
                    }
                ).popover('show');
                jQuery(element).siblings('.popover').addClass(this.boxErrorClass);
            },
            onfocusout:function(element,event){
                jQuery(element).parent().removeClass(this.inputErrorClass);
                jQuery(element).popover('destroy');
            }
        }, opts);

        return this.validate(options);
    };
}

var Messages={
    configErrors:function(laravelMessages,title){

        var messages=[];

        for(var property in laravelMessages){
            if(laravelMessages.hasOwnProperty(property)){
                messages=(messages.concat(laravelMessages[property]));
            }
        }
        function show(){
            var time=0;
            for(var i=0;i<messages.length;i++){

                setTimeout(function(message){
                    function showInternal(){
                        jQuery.pnotify({
                            title: title,
                            text: message,
                            type: 'error'
                        });
                    }
                    return {
                        showInternal:showInternal
                    }

                }(messages[i]).showInternal,time+=500);
            }
        }
        return {
            show:show
        }
    }
}

var Mercatino = {};

/**
 * @param object Example {title:'the title', message:'the message', type:'success|warning|error'}
 */
Mercatino.showFlashMessage = function(object){
    jQuery.pnotify({
        title: object.title,
        text: object.message,
        type: object.type
    });
};

Mercatino.modalConfirm = {
    show: function(title, content, url){
        jQuery('#modal-confirm .modal-header h3').html(title);
        jQuery('#modal-confirm .modal-body p').html(content);
        jQuery('#modal-confirm .modal-footer a.danger').attr('href', url);
        jQuery('#modal-confirm').modal('show');
    },
    hide: function(){
        jQuery('#modal-confirm').modal('hide')
    }
};

Mercatino.resetForm={
    show:function(token,email){
        jQuery('#modalReset').modal('show');
        jQuery('.email').html(email);
        jQuery('#resetEmail').val(email);
        jQuery('#resetValidation').val(token);
        jQuery('.reset-send').button('reset');
        jQuery('#resetForm').get(0).reset();
        jQuery('.reset-question').removeClass('hide');
        jQuery('.reset-answer').addClass('hide');
    },
    send:function(){
        var form=jQuery('#resetForm');
        var formData = form.serializeObject();
        var url = form.attr('action');

        jQuery('.reset-send').button('loading');

        jQuery.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(result) {

                jQuery('.reset-question').addClass('hide');
                jQuery('.reset-answer')
                    .css('opacity',0)
                    .removeClass('hide').
                    animate({'opacity':1},500);
                setTimeout(function(){
                    window.location.href = "/";
                },3000);

            },
            error: function(result) {
                var data = result.responseJSON;
                if (data.status_code == 'validation') {
                    for (var i = 0; i < data.errors.length; i++){
                        Mercatino.showFlashMessage({title:'', message: data.errors[i], type:'error'});
                    }
                    jQuery('.reset-send').button('reset');
                    return false;
                }

                if(data.status_code='error_token'){
                    Mercatino.showFlashMessage({title:'', message: data.status_value, type:'error'});
                    setTimeout(function(){
                        window.location.href = "/";
                    },3000);
                    return false;
                }

                if (data.status_code == 'invalid_token') {
                    window.location.href = "/";
                }
            }
        });
    }
};

Mercatino.reminderForm = {
    show:function(){
        jQuery('#modalReminder').modal('show');

        jQuery('.reminder-cancel').attr('disabled',false);
        jQuery('.reminder-send').button('reset');

        jQuery('#reminderForm').get(0).reset();
        jQuery('.reminder-question').removeClass('hide');
        jQuery('.reminder-answer').addClass('hide');
    },
    hide:function(){
        jQuery('#modalReminder').modal('hide');
    },
    send:function(){
        if($('.reminder-question').hasClass('hide')){
            Mercatino.reminderForm.hide();
            return false;
        }

        var form=jQuery('#reminderForm');
        var formData = form.serializeObject();
        var url = form.attr('action');

        jQuery('.reminder-send').button('loading');

        jQuery.ajax({
            url: jQuery('#reminderValidation').val(),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(result) {
                jQuery('.reminder-question').addClass('hide');
                jQuery('.reminder-answer')
                    .css('opacity',0)
                    .removeClass('hide').
                    animate({'opacity':1},500);

                jQuery('.reminder-cancel').attr('disabled',true);
                jQuery('.reminder-send').button('complete');


                jQuery.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(result) {
                        Mercatino.showFlashMessage({title:'',message:result.status_value,type:'success'});
                    },
                    error: function(result) {
                        var data = result.responseJSON;
                        if (data.status_code == 'validation') {
                            for (var i = 0; i < data.errors.length; i++){
                                Mercatino.showFlashMessage({title:'', message: data.errors[i], type:'error'});
                            }
                            return false;
                        }

                        if (data.status_code == 'invalid_token') {
                            window.location.href = "/";
                        }
                    }
                });
            },
            error: function(result) {
                var data = result.responseJSON;
                if (data.status_code == 'validation') {
                    for (var i = 0; i < data.errors.length; i++){
                        Mercatino.showFlashMessage({title:'', message: data.errors[i], type:'error'});
                    }
                    jQuery('.reminder-send').button('reset');
                    return false;
                }

                if (data.status_code == 'invalid_token') {
                    window.location.href = "/";
                }
            }
        });
    }
}


Mercatino.loginForm = {
    init: function(title, content, url){

        jQuery('#login-form').validateBootstrap({
            placement:'bottom'
        });

        jQuery('#login-form')[0].reset();

        jQuery('#login-form input').bind('keydown', function(event){
            if (event.which == 13) {
                Mercatino.loginForm.send();
            };
        })


    },
    hide: function(){
        jQuery('#modal-register').modal('hide');
    },
    send: function(){

        if (!jQuery('#login-form').valid()){
            return false;
        }

        this.loadingState();

        var formData = jQuery('#login-form').serializeObject();
        var url = jQuery('#login-form').attr('action');

        jQuery.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            context: this, //chance this in closures to passed object
            success: function(result) {
                if (result.redirect_url == '') {
                    window.location.reload();
                } else {
                    window.location.href = result.redirect_url;
                }
            },
            error: function(result) {
                this.defaultState(); //Rob: this make reference to context object, defined on ajax call
                var data = result.responseJSON;
                if (data.status_code == 'validation') {
                    for (var i = 0; i < data.errors.length; i++){
                        Mercatino.showFlashMessage({title:'', message: data.errors[i], type:'error'});
                    }
                    return false;
                }

                if (data.status_code == 'invalid_token') {
                    window.location.href = "/";
                }

                if(data.status_code == 'inactive'){
                    Mercatino.showFlashMessage({title:'',message:data.values.message, type:'error'});
                }
            }
        });
    },
    defaultState: function() {
        jQuery('#header_login_form').show();
        jQuery('#header_login_preload').hide();
    },
    loadingState: function() {
        jQuery('#header_login_form').hide();
        jQuery('#header_login_preload').show();
    }
};

Mercatino.tronSkin=function(){
    var a = this.angle(this.cv)  // Angle
        , sa = this.startAngle          // Previous start angle
        , sat = this.startAngle         // Start angle
        , ea                            // Previous end angle
        , eat = sat + a                 // End angle
        , r = true;

    this.g.lineWidth = this.lineWidth;

    this.o.cursor
        && (sat = eat - 0.3)
    && (eat = eat + 0.3);

    if (this.o.displayPrevious) {
        ea = this.startAngle + this.angle(this.value);
        this.o.cursor
            && (sa = ea - 0.3)
        && (ea = ea + 0.3);
        this.g.beginPath();
        this.g.strokeStyle = this.previousColor;
        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
        this.g.stroke();
    }

    this.g.beginPath();
    this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
    this.g.stroke();

    this.g.lineWidth = 2;
    this.g.beginPath();
    this.g.strokeStyle = this.o.fgColor;
    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
    this.g.stroke();

    return false;
};


Mercatino.longTextTooltips = function() {
    jQuery('.masterTooltip').hover(function(){
        // Hover over code
        var title = jQuery(this).attr('title');
        console.log(title);
        jQuery(this).data('tipText', title).removeAttr('title');
        jQuery('<p class="tooltip"></p>').text(title).appendTo('#body').show();
    }, function() {
        // Hover out code
        jQuery(this).attr('title', jQuery(this).data('tipText'));
        jQuery('.tooltip').remove();
    }).mousemove(function(e) {

        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
            console.log(mousex);
            console.log(mousey);
            jQuery('.tooltip')
            .css({ top: 0, left: 0 })
    });
}



