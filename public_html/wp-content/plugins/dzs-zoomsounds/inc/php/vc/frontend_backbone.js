window.InlineShortcodeView_zoomtimeline = window.InlineShortcodeView.extend({
    render: function() {
        window.InlineShortcodeView_zoomtimeline.__super__.render.call(this);

        var _tel = this.$el;


        _tel.find('.zoomtimeline').each(function(){
            var _t = jQuery(this);

            if(_t.hasClass('inited')){

            }else{
                if(jQuery.fn.zoomtimeline){

                    _t.zoomtimeline();
                }else{
                    console.log('zoomtimeline not definied');
                }
            }



        });
        return this;
    }
});


