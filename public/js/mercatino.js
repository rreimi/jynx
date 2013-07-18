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

    jQuery.fn.validateBootstrap = function(){
        var eClass = 'alert-error';
        var sClass = 'alert-success';
        var wClass = 'alert-warning';

        return this.validate({
//            unhighlight: function(element, errorClass, validClass) {
//                if (element.type === 'radio') {
//                    this.findByName(element.name).parent('div').parent('div').removeClass(eClass).addClass(sClass);
//                } else {
//                    $(element).parent('div').parent('div').removeClass(eClass).addClass(sClass);
//                    $(element).popover('hide');
//                }
//            },
//            highlight: function(element, errorClass, validClass) {
//                if (element.type === 'radio') {
//                    this.findByName(element.name).parent('div').parent('div').addClass(eClass).removeClass(sClass);
//                } else {
//                    $(element).parent('div').parent('div').addClass(eClass).removeClass(sClass);
//                }
//            },
            errorPlacement: function(error, element) {
                jQuery(element).popover('show');
                setTimeout(function(element){

                    function hide(){
                        $(element).popover('hide');
                    }

                    return {
                        hide:hide
                    }

                }(element).hide,3000);
            },
            onkeyup: false,
            onclick: false,
            onsubmit: true

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
                        $.pnotify({
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