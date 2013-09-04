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

    jQuery.fn.validateBootstrap = function(options){

        options || (options = {});

        var boxErrorClass = options.boxErrorClass || 'alert-error';
        var inputErrorClass = options.inputErrorClass || 'error';
        var placement = options.placement || 'right';
        var messages = options.messages || {};

        return this.validate({
            errorPlacement: function(error, element) {
                jQuery(element).parent().addClass(inputErrorClass);
                jQuery(element).popover('destroy');
                jQuery(element).popover(
                    {
                        content:error.text(),
                        placement:jQuery(element).data('placement') || placement
                    }
                ).popover('show');
                jQuery(element).siblings('.popover').addClass(boxErrorClass);
            },
            onfocusout:function(element,event){
                jQuery(element).parent().removeClass(inputErrorClass);
                jQuery(element).popover('hide');
            },
            messages:messages,
            onkeyup:false,
            onclick:false,
            focusInvalid:false,
            focusCleanup:true,
            onsubmit:true

        });
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

        var formData = jQuery('#register-form').serializeObject();
        var url = jQuery('#register-form').attr('action');

        jQuery.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(result) {
                var data = result.responseJSON;
                window.location.href = data.redirect_url;
            },
            error: function(result) {
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
    }
};

Mercatino.loginForm = {
    show: function(title, content, url){
        //jQuery('#modal-confirm .modal-header h3').html(title);
        //jQuery('#modal-confirm .modal-body p').html(content);
        //jQuery('#modal-confirm .modal-footer a.danger').attr('href', url);
        jQuery('#login-form')[0].reset();
        jQuery('#modal-register').modal('show');
    },
    hide: function(){
        jQuery('#modal-register').modal('hide');
    },
    send: function(){

        var formData = jQuery('#login-form').serializeObject();
        var url = jQuery('#login-form').attr('action');

        jQuery.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(result) {
                var data = result.responseJSON;
                window.location.href = data.redirect_url;
            },
            error: function(result) {
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
}




