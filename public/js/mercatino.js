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
        var placement = options.placement || 'right';
        var messages = options.messages || {};

        return this.validate({
            highlight:function(element, errorClass){

            },
            errorPlacement: function(error, element) {
                jQuery(element).popover('destroy');
                jQuery(element).popover(
                    {
                        content:error.text(),
                        placement:jQuery(element).attr('data-placement') || placement
                    }
                ).popover('show');
                jQuery(element).siblings('.popover').addClass(boxErrorClass);
            },
            onfocusout:function(element,event){
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




