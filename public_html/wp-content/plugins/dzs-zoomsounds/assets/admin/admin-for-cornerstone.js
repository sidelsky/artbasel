jQuery(document).ready(function($){











    setTimeout(function(){


        if(window.cs){

            cs.listenTo( cs.events, 'inspect:element', function(e,e2,e3){








                if(e.attributes._type=='dzsap'){

                    $('.cs-control[data-name="source"]').each(function(){
                        var _t = $(this);

                        _t.find('input[type="text"]').addClass('input-big-image upload-target-prev upload-type-audio ');

                        if(_t.find('.upload-for-target').length==0){

                            _t.find('input[type="text"]').after('<a href="#" class="button button-secondary dzs-wordpress-uploader">Upload</a>');
                        }

                        setTimeout(function(){
                            var _t2 = $('.cs-control[data-name="source"]').eq(0);
                            console.warn(_t2,_t2.find('.dzs-wordpress-uploader').length);
                            if(_t2.find('.dzs-wordpress-uploader').length==0){

                                _t2.find('input[type="text"]').addClass('input-big-image upload-target-prev upload-type-audio ');
                                _t2.find('input[type="text"]').after('<a href="#" class="button button-secondary dzs-wordpress-uploader">Upload</a>');


                            }
                        },300)
                    })
                }
            } );
        }
    },10);























});

