

var browserversion = parseInt(jQuery.browser.version);
(function($) {

    var types = ['DOMMouseScroll', 'mousewheel'];

    $.event.special.mousewheel = {
        setup: function() {
            if ( this.addEventListener ) {
                for ( var i=types.length; i; ) {
                    this.addEventListener( types[--i], handler, false );
                }
            } else {
                this.onmousewheel = handler;
            }
        },
    
        teardown: function() {
            if ( this.removeEventListener ) {
                for ( var i=types.length; i; ) {
                    this.removeEventListener( types[--i], handler, false );
                }
            } else {
                this.onmousewheel = null;
            }
        }
    };

    $.fn.extend({
        mousewheel: function(fn) {
            return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
        },
    
        unmousewheel: function(fn) {
            return this.unbind("mousewheel", fn);
        }
    });


    function handler(event) {
        var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
        event = $.event.fix(orgEvent);
        event.type = "mousewheel";
    


        if ( event.wheelDelta ) {
            delta = event.wheelDelta/120;
        }
        if ( event.detail     ) {
            delta = -event.detail/3;
        }

        deltaY = delta;
    

        if ( (orgEvent.axis !== undefined && orgEvent.axis === orgEvent.VERTICAL_AXIS) || (orgEvent.AXIS !== undefined && orgEvent.AXIS === orgEvent.VERTICAL_AXIS) ) {
        
            if(orgEvent.detail!=undefined){
                deltaY = -1 * orgEvent.detail;
            }
        }
    
    
        if ( (orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) || (orgEvent.AXIS !== undefined && orgEvent.AXIS === orgEvent.HORIZONTAL_AXIS) ) {
        
            deltaY = 0;
            deltaX = -1*delta;
            if(jQuery.browser.mozilla && browserversion>=10){
                if(orgEvent.detail!=undefined){
                    deltaX = orgEvent.detail;
                }
            }
        }
    

        if ( orgEvent.wheelDeltaY !== undefined ) {
            deltaY = orgEvent.wheelDeltaY/120;
        }
        if ( orgEvent.wheelDeltaX !== undefined ) {
            deltaX = -1*orgEvent.wheelDeltaX/120;
        }
    

        // Add event and delta to the front of the arguments
        args.unshift(event, delta, deltaX, deltaY);
    
        return $.event.handle.apply(this, args);
    }

})(jQuery);






/*
 * Author: Digital Zoom Studio
 * Website: http://digitalzoomstudio.net/
 * Portfolio: http://codecanyon.net/user/ZoomIt/portfolio
 */

(function($) {
    $.fn.scroller = function(o) {

        var defaults = {
            isbody : 'off'
            , 
            totalWidth : undefined,
            totalwidth : undefined,
            settings_multiplier : 3,
            settings_skin : 'skin_default',
            settings_scrollbar : 'on',
            settings_scrollbyhover : 'off',
            settings_fadeoutonleave : 'off',
            settings_replacewheelxwithy : 'off',
            settings_refresh : 0,
            settings_autoheight : 'off',
            settings_forcesameheight : 'off',
            settings_fullwidth : 'off',
            settings_hidedefaultsidebars : 'off',
            settings_dragmethod : 'drag',
            settings_autoresizescrollbar : 'off',
            scrollBg : 'off',
            force_onlyy : 'off',
            objecter : undefined
            , 
            settings_smoothing : 'off'//deprecated - use the easing class on the scroll-con for easing
        };
        var o = $.extend(defaults, o);

        this.each(function() {


            var totalWidth = 0;
            //total width of the container, this is usually taken from the css of the div
            var totalHeight = 0;
            var comWidth = 0;
            // total width of the real element
            var comHeight = 0;
            var ww = 0
            var wh = 0;
            var inner;
            //subdiv of the container ( real content )
            var outer;
            // subdiv of the container
            var auxdeltax = 0;
            var auxdeltay = 0;
            var viewIndexWidth = 0;
            var scrollIndexY = 0;
            var scrollIndexX = 0;
            var scrollbar_height = 0;
            var scrollbary;
            var scrollbary_bg;
            var scrollbarx;
            var scrollbarx_bg;
            var cthis = $(this);
            var mousex = 0;
            var mousey = 0;
            var scrollbary_pressed = false;
            var scrollbarx_pressed = false;
            var scrollbary_psize = 0;
            var scrollbarx_psize = 0;
            var scrollbarx_dragx=0;
            var scrollbarx_draglocalx=0;
            var scrollbary_dragy=0;
            var scrollbary_draglocaly=0;

            var viewIndexX = 0;
            var viewIndexY = 0;

            var scrollbufferX = false;
            var scrollbufferY = false;

            var dir_hor = true;
            var dir_ver = true;
            var percomWidth = 0;

            var iOuter;

            var duration_smoothing = 60;
                        
            if(o.isbody=='on'){
                o.settings_refreshonresize = 'on';
                cthis.wrapInner('<div class="inner"></div>');
                cthis.addClass('scroller-con');
                if(is_ios()) {
                    return;
                }
            }
            inner = cthis.find('.inner');
            cthis.addClass(o.settings_skin);
            if(is_ios()) {
                cthis.css('overflow', 'auto')

            } else {
                inner.wrap('<div class="scroller"></div>')
            }
            outer = cthis.find('.scroller');

            init();


            function init() {
                if(o.totalWidth != undefined)
                    totalWidth = o.totalWidth;
                else
                    totalWidth = cthis.width();

                if(o.totalHeight != undefined)
                    totalHeight = o.totalHeight;
                else
                    totalHeight = cthis.height();

                if(o.settings_fullwidth == 'on')
                    totalWidth = jQuery(window).width();

                if(o.settings_autoheight == 'on'){
                                    
                    totalHeight = (inner.children().children().eq(0).height());

                }
                                
                if(o.isbody=='on'){

                    totalHeight = jQuery(window).height();
                }

                outer.css({
                    'width' : totalWidth,
                    'height' : totalHeight
                })
                                
                if(is_ios() == false) {
                    cthis.css({
                        'width' : totalWidth,
                        'height' : totalHeight,
                        'overflow' : 'inherit'
                    })
                }

                if(is_ios()) {

                    cthis.css({
                        'width' : totalWidth,
                        'height' : totalHeight,
                        'overflow' : 'auto'
                    })
                }
                if(jQuery.browser.msie != undefined && jQuery.browser.version == 7)
                    cthis.css('overflow', 'visible');
                inner.css({

                    })

                if(o.settings_hidedefaultsidebars == 'on') {
                    cthis.css('overflow', 'hidden')
                    $('html').css('overflow', 'hidden')
                }
                comWidth = inner.width();
                comHeight = inner.height();
                if(inner.find('.real-inner').length == 1) {
                    comWidth = inner.find('.real-inner').width();
                    comHeight = inner.find('.real-inner').height();
                }

                if(o.settings_forcesameheight == 'on') {
                    totalHeight = comHeight;
                    cthis.height(totalHeight);
                    outer.height(totalHeight);
                }

                if(o.scrollBg == 'on') {

                    comHeight = cthis.height();
                    totalHeight = $(window).height();
                }


                if(comHeight <= totalHeight)
                    dir_ver = false;
                if(comWidth <= totalWidth)
                    dir_hor = false;
                if(o.settings_fullwidth == 'on')
                    dir_hor = true;

                if(o.force_onlyy == 'on') {
                    dir_ver = true;
                    dir_hor = false;
                }

                if(dir_ver == false && dir_hor == false)
                    return;

                var realparent;
                realparent = cthis;
                if(is_ios() == true) {
                    cthis.wrap('<div class="iWrapper scroller-con"></div>');
                    realparent = cthis.parent();
                    realparent.css({
                        'width' : totalWidth,
                        'height' : totalHeight,
                        'overflow' : 'visible'
                    })
                    realparent.addClass(o.settings_skin);
                }
                if(o.settings_scrollbar == 'on') {

                    if(dir_ver) {
                        realparent.append('<div class="scrollbary_bg"></div>')
                        realparent.append('<div class="scrollbary"></div>')
                    }
                    if(dir_hor) {
                        realparent.append('<div class="scrollbarx_bg"></div>')
                        realparent.append('<div class="scrollbarx"></div>')
                    }
                }

                if(dir_ver) {
                    scrollbary = realparent.children('.scrollbary');
                    scrollbary_bg = realparent.children('.scrollbary_bg');
                    scrollbary_psize = scrollbary.height();
                    if(o.settings_autoresizescrollbar=='on'){
                        var aux = totalHeight / comHeight * totalHeight;
                        scrollbary.css('height', aux);
                        scrollbary_psize = aux;
                    }
                    scrollbary_bg.css('height', totalHeight);

                    if(o.settings_fadeoutonleave == 'on') {
                        scrollbary.css('opacity', 0);
                        scrollbary_bg.css('opacity', 0);
                    }
                }

                if(dir_hor) {
                    scrollbarx = realparent.children('.scrollbarx');
                    scrollbarx_bg = realparent.children('.scrollbarx_bg');
                    scrollbarx_psize = scrollbarx.width();

                    if(o.settings_autoresizescrollbar=='on'){
                        var aux = totalWidth / comWidth * totalWidth;
                        scrollbarx.css('width', aux);
                        scrollbarx_psize = aux;
                    }
                    scrollbarx_bg.css('width', totalWidth);

                    if(o.settings_fadeoutonleave == 'on') {
                        scrollbarx.css('opacity', 0);
                        scrollbarx_bg.css('opacity', 0);
                    }
                    if(comWidth <= totalWidth && o.settings_fullwidth == 'on') {
                        scrollbarx.hide();
                        scrollbarx_bg.hide();
                    }
                }

                if(cthis.css('opacity') == 0) {
                    cthis.animate({
                        'opacity' : 1
                    }, 600)
                    cthis.parent().children('.preloader').fadeOut('slow');
                }
                            
                                
				
                if(percomWidth == 0){
                    percomWidth = comWidth + 50;
                }
                if(is_ios() == true) {

                    cthis.css({
                        'width' : totalWidth,
                        'height' : totalHeight,
                        'overflow' : 'auto'
                    })
                    inner.css({
                        'width' : percomWidth
                    })
                }

                if(o.objecter !=undefined){

                    o.objecter.reinit = reinit;
                    o.objecter.scrollToTop = scrollToTop;
                }
                                
                if(o.settings_refresh > 0){
                    setInterval(reinit, o.settings_refresh);
                }
                if(o.settings_refreshonresize == 'on')
                    $(window).resize(reinit);
                                    
                                    
            }

            function scrollToTop() {
                viewIndexY=0;
                scrollIndexY=0;
                animateScrollbar();
            }
            function reinit() {
                ww = jQuery(window).width();
                wh = jQuery(window).height();
                if(o.isbody=='on'){
                    totalWidth = ww;
                    cthis.css({
                        'width' : ww
                        , 'height' : wh
                    })
                }
                var auxperc = 0;
                var auxpery = 0;
                if(dir_hor == true) {
                    auxperc = parseInt(scrollbarx.css('left')) / totalWidth;
                    if(o.settings_fullwidth == 'on') {
                        totalWidth = $(window).width();
                        cthis.css('width', totalWidth);
                    }
                                        
                    if(o.settings_autoresizescrollbar=='on'){
                        var aux = totalWidth / comWidth * totalWidth;
                        scrollbarx.css('width', aux);
                        scrollbarx_psize = aux;
                    }
                }
                if(dir_ver == true) {
                    auxpery = parseInt(scrollbary.css('top')) / totalHeight;
                    if(o.settings_fullheight == 'on') {
                        totalHeight = $(window).height();
                        cthis.css('height', totalHeight);
                    }
                    if(o.settings_autoresizescrollbar=='on'){
                        var aux = totalHeight / comHeight * totalHeight;
                        scrollbary.css('height', aux);
                        scrollbary_psize = aux;
                    }
                }
                totalHeight = cthis.height();
                comWidth = inner.width();
                comHeight = inner.height();
                if(inner.find('.real-inner').length == 1) {
                    comWidth = inner.find('.real-inner').width();
                    comHeight = inner.find('.real-inner').height();
                }

                percomWidth = comWidth + 50;

                outer.css({
                    'width' : totalWidth,
                    'height' : totalHeight
                })
                if(is_ios() == false) {
                    cthis.css({
                        'width' : totalWidth,
                        'height' : totalHeight,
                        'overflow' : 'inherit'
                    })
                } else {

                    cthis.css({
                        'width' : totalWidth,
                        'height' : totalHeight,
                        'overflow' : 'auto'
                    })
                    inner.css({
                        'width' : percomWidth
                    })
                }
                if(dir_hor == true) {
                    scrollbarx_bg.css('width', totalWidth);
                }
                if(dir_hor && totalWidth > comWidth && scrollbarx.css('display') == 'block') {
                    scrollbarx_bg.hide();
                    scrollbarx.hide();
                    auxperc = 0;
                }
                if(dir_hor && totalWidth < comWidth && scrollbarx.css('display') == 'none') {
                    scrollbarx_bg.show();
                    scrollbarx.show();
                    auxperc = 0;
                }
                if(dir_ver == true) {
                    scrollbary_bg.css('height', totalHeight);
                }

                animateScrollbar();
                if(dir_hor && totalWidth > comWidth && o.settings_fullwidth == 'on') {
                    inner.css('left', 0)
                }
                if(o.settings_fullwidth == 'on') {
                    scrollbarx.css('left', auxperc * totalWidth);
                }
            }


            $.fn.scroller.reinit = function() {
                reinit();
            }
            if(o.settings_scrollbyhover != 'on' && is_ios() == false)
                cthis.mousewheel(function(event, delta, deltaX, deltaY) {
                    scrollbufferY=false;


                    auxdeltax = -Math.floor(deltaX);
                    auxdeltay = Math.floor(deltaY);

                    if(o.settings_replacewheelxwithy == 'on')
                        auxdeltax = Math.floor(deltaY);

                    if(dir_ver) {
                        if(deltaY > 0 && auxdeltay < deltaY)
                            auxdeltay++;

                        if(deltaY < 0 && auxdeltay > deltaY)
                            auxdeltay--;
                        viewIndexY += (auxdeltay * o.settings_multiplier );
                        scrollIndexY = viewIndexY / (comHeight - totalHeight) * -(totalHeight - scrollbary_psize);
                    }

                    if(dir_hor) {

                        if(deltaX < 0 && auxdeltax > deltaX)
                            auxdeltax++;

                        if(deltaX > 0 && auxdeltax < deltaX)
                            auxdeltax--;

                        if(o.settings_replacewheelxwithy == 'on' && deltaY > 0 && auxdeltax < deltaY)
                            auxdeltax++;
                        viewIndexX += (auxdeltax * o.settings_multiplier );

                        scrollIndexX = viewIndexX / (comWidth - totalWidth) * -(totalWidth - scrollbarx_psize);
                    }

                    animateScrollbar();

                    if(scrollbufferY==false){
                        event.stopPropagation();
                        event.preventDefault();
                    }
                                    
                });
            if(scrollbary_bg && is_ios() == false)
                scrollbary_bg.mousedown(function(event) {
                    scrollbary_pressed = true;
                    return false;
                })
            if(scrollbary && is_ios() == false)
                scrollbary.mousedown(function(event) {
                    scrollbary_pressed = true;
                    return false;
                })
            if(scrollbarx_bg && is_ios() == false)
                scrollbarx_bg.mousedown(function(event) {
                    scrollbarx_pressed = true;
                    return false;
                })
            if(scrollbarx && is_ios() == false)
                scrollbarx.mousedown(function(e) {
                    scrollbarx_pressed = true;
                    scrollbarx_dragx = parseInt($(this).css('left'));
                    scrollbarx_draglocalx = (e.pageX - cthis.offset().left) - scrollbarx_dragx;
                    return false;
                })
            if(is_ios() == false){
                jQuery(document).mousemove(function(e) {
                    mousex = (e.pageX - cthis.offset().left);
                    mousey = (e.pageY - cthis.offset().top);
                    if(o.settings_scrollbyhover == 'on' && (mousex < 0 || mousey < 0 || mousex > totalWidth + 20 || mousey > totalHeight + 20))
                        return;
                    if(dir_ver == true && (scrollbary_pressed == true || o.settings_scrollbyhover == 'on')) {
                        if(o.settings_dragmethod=='normal'){
                            scrollIndexY = mousey / totalHeight * (totalHeight - scrollbary_psize);
                            viewIndexY = mousey / totalHeight * (totalHeight - comHeight);
                        }
                        if(o.settings_dragmethod=='drag'){
                            scrollIndexY = scrollbary_dragy + (mousey - scrollbary_dragy) - scrollbary_draglocaly;
                            viewIndexY = (scrollIndexY / (-(totalHeight - scrollbary_psize))) * (comHeight - totalHeight);
                        }
                            viewIndexY = parseInt(viewIndexY, 10);
                        animateScrollbar();
                    }

                    if(dir_hor == true && (scrollbarx_pressed == true || o.settings_scrollbyhover == 'on')) {
                        if(o.settings_dragmethod=='normal'){
                            scrollIndexX = mousex / totalWidth * (totalWidth - scrollbarx_psize);
                            viewIndexX = mousex / totalWidth * (totalWidth - comWidth);
                        }
                        if(o.settings_dragmethod=='drag'){
                                                    
                            scrollIndexX = scrollbarx_dragx + (mousex - scrollbarx_dragx) - scrollbarx_draglocalx;
                            viewIndexX = (scrollIndexX / (-(totalWidth - scrollbarx_psize))) * (comWidth - totalWidth);
                        }
                        animateScrollbar();
                    }

                    if(o.settings_fadeoutonleave == 'on') {
                        scrollbary.animate({
                            'opacity' : 1
                        }, {
                            queue : false,
                            duration : 500
                        });
                        scrollbary_bg.animate({
                            'opacity' : 1
                        }, {
                            queue : false,
                            duration : 500
                        });
                    }

                })
            }
            if(is_ios() == false){
                jQuery(document).mouseup(function(event) {

                    scrollbary_pressed = false;
                    scrollbarx_pressed = false;
                })
            }
            function animateScrollbar() {

                if(dir_ver) {
                    if(viewIndexY > 0)
                        viewIndexY = 0;
                    if(viewIndexY < -(comHeight - totalHeight))
                        viewIndexY = -(comHeight - totalHeight);

                    if(scrollIndexY < 0){
                        scrollIndexY = 0;
                        scrollbufferY=true;
                    }
                    if(scrollIndexY > (totalHeight - scrollbary_psize)){
                        scrollIndexY = (totalHeight - scrollbary_psize);
                        scrollbufferY=true;
                    }

                    if(o.settings_smoothing != 'on') {

                        inner.css({
                            'top' : viewIndexY
                        })
                        scrollbary.css({
                            'top' : scrollIndexY
                        })
                        if(o.scrollBg == 'on') {
                            cthis.css('background-position', 'center ' + viewIndexY + 'px')
                        }

                    } else {
                        inner.animate({
                            'top' : viewIndexY
                        }, {
                            queue : false,
                            duration : duration_smoothing
                        })
                        scrollbary.animate({
                            'top' : scrollIndexY
                        }, {
                            queue : false,
                            duration : duration_smoothing
                        })
                    }
                }

                if(dir_hor) {
                    if(viewIndexX > 0)
                        viewIndexX = 0;
                    if(viewIndexX < -(comWidth - totalWidth))
                        viewIndexX = -(comWidth - totalWidth);


                    if(scrollIndexX < 0)
                        scrollIndexX = 0;
                    if(scrollIndexX > (totalWidth - scrollbarx_psize))
                        scrollIndexX = (totalWidth - scrollbarx_psize);

                    if(o.settings_smoothing != 'on') {
                        inner.css({
                            'left' : viewIndexX
                        })
                        scrollbarx.css({
                            'left' : scrollIndexX
                        })

                    } else {
                        inner.animate({
                            'left' : viewIndexX
                        }, {
                            queue : false,
                            duration : duration_smoothing
                        })
                        scrollbarx.animate({
                            'left' : scrollIndexX
                        }, {
                            queue : false,
                            duration : duration_smoothing
                        })
                    }

                }
            }

            if(o.settings_fadeoutonleave == 'on' && is_ios() == false) {
                cthis.mouseleave(function(e) {

                    scrollbary.animate({
                        'opacity' : 0
                    }, {
                        queue : false,
                        duration : 500
                    });
                    scrollbary_bg.animate({
                        'opacity' : 0
                    }, {
                        queue : false,
                        duration : 500
                    });
                })
            }
            if(is_ios() == true) {
                setInterval(tick, 70)
            }
            function tick() {
                // -- only for ios
                var iW = cthis.width() - inner.width();
                var iL = inner.position().left;
                var iH = cthis.height() - inner.height();
                var iT = inner.position().top;
                scrollIndexX = (iL / iW) * (totalWidth - scrollbarx_psize);
                scrollIndexY = (iT / iH) * (totalHeight - scrollbarx_psize);
                animateScrollbar();
            }

            return this;
        });
    };
})(jQuery);
function is_ios() {

    return ((navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPod") != -1) || (navigator.platform.indexOf("iPad") != -1 || (navigator.platform.indexOf("Android") != -1))
        )
}