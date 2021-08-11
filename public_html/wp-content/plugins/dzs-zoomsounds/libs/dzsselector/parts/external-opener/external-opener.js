'use strict';
function documentReady(callback) {
  new Promise((resolutionFunc, rejectionFunc) => {
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      resolutionFunc('interactive')
    }
    document.addEventListener('DOMContentLoaded', () => {
      resolutionFunc('DOMContentLoaded')
    }, false);
    setTimeout(() => {
      resolutionFunc('timeout')
    }, 5000);
  }).then(resolution => {
    callback(resolution);
  }).catch(err => {
    callback(err)
  });
}

documentReady(()=>{


  const getWrapperClass= ($wrapperMain)=>{
    const styleMatches = / style--(.*?)(?: |$)/g.exec($wrapperMain.attr('class'));


    return styleMatches && styleMatches[1];
  }

  /**
   *
   * @param {DzsSelector} selfInstance
   */
  window.opener_externalOpenerToggle = (selfInstance)=>{

    if(selfInstance.$openerExternalArea.hasClass('opener-external-opened')){
      window.opener_externalOpenerClose(selfInstance);
    }else{

      selfInstance.$openerExternalArea.find('.external-content').append(selfInstance.$openerMain);
      selfInstance.$openerExternalArea.addClass('opener-external-opened');
      const styleClass = getWrapperClass(selfInstance.$wrapperMain);

      if(styleClass){
        selfInstance.$openerExternalArea.addClass(`style--${styleClass}`)
      }
      if(selfInstance.$openerExternalArea.attr('data-view-max-height')){
        selfInstance.$openerExternalArea.removeClass('is-extended');
        selfInstance.$openerExternalArea.find('.external-content').css({
          'max-height': `${selfInstance.$openerExternalArea.attr('data-view-max-height')}px`
        })
      }

      selfInstance.$openerExternalArea.data('dzssel-opener-wrapper', selfInstance.$openerWrap);
    }
  }
  /**
   *
   * @param {DzsSelector} selfInstance
   */
  window.opener_externalOpenerClose = (selfInstance)=>{

    if(selfInstance.$openerExternalArea.data('dzssel-opener-wrapper')){
      selfInstance.$openerExternalArea.data('dzssel-opener-wrapper').append(selfInstance.$openerExternalArea.find('.external-content').children());
      selfInstance.$openerExternalArea.removeClass('opener-external-opened');
    }
  }




  document.addEventListener('click', function (event) {
    var t = event.target;
    while (t && t.matches && t.parentNode) {
      if (t.matches('.external-content--show-more')) {
        handle_mouse.call(t, event);
      }
      t = t.parentNode;
    }
  });

  function handle_mouse(e) {

    // todo: transfer to singleton
    if (this.classList.contains('external-content--show-more')) {
      if(this.parentElement.classList.contains('is-extended')){

        this.parentElement.classList.remove('is-extended');
        this.parentElement.querySelector('.external-content').style.maxHeight = `${this.parentElement.getAttribute('data-view-max-height')}px`;
      }else{

        this.parentElement.querySelector('.external-content').style.maxHeight = '300px';
        this.parentElement.classList.add('is-extended');
      }
    }

  }
});