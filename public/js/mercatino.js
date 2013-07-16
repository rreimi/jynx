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
}

var Messages={
    configErrors:function(laravelMessages,title){

        var messages=[];
        var title=title;

        for(var property in laravelMessages){
            messages=(messages.concat(laravelMessages[property]));
        }

        function show(){
            for(var i=0;i<messages.length;i++){
                $.pnotify({
                    title: title,
                    text: messages[i],
                    type: 'error'
                });
            }
        }

        return {
            show:show
        }
    }
}