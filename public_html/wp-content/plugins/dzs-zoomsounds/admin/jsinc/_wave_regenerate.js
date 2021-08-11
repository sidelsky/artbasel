exports.wave_regenerate_init = function () {




  var $ = jQuery;



  function updateFields() {
    if(document.querySelector('*[name=track_id]')){

      $('*[name=wavedata_track_url]').val($('*[name=track_url]').val())
      $('*[name=wavedata_track_id]').val($('*[name=track_id]').val())
      $('*[name=wavedata_track_url_id]').val($('*[name=track_url_id]').val())
    }else{

      $('*[name=wavedata_track_url]').val(window.get_query_arg(window.location.href, 'track_url'))
      $('*[name=wavedata_track_id]').val(window.get_query_arg(window.location.href, 'track_id'))
      $('*[name=wavedata_track_url_id]').val(window.get_query_arg(window.location.href, 'track_url_id'))
    }
  }

  updateFields();
  $('*[name=track_url],*[name=track_id],*[name=track_url_id]').on('keyup', function () {
    updateFields();
  })


  window.dzsap_admin_waveforms_submitPcmData = submitPcmData;

  $(document).on('submit', '.track-waveform-meta', handle_submit);
  $(document).on('click', '.regenerate-waveform', handle_mouse);


  function handle_mouse(e) {

    var _t = ($(this));

    if (e.type === 'click') {
      if (_t.hasClass('regenerate-waveform')) {

        _t.attr('data-player-source', $('#dzsap_woo_product_track').val()); // -- tbc


        var regenerateUrl = dzsap_settings.admin_url + 'admin.php';

        regenerateUrl = add_query_arg(regenerateUrl, 'page', 'dzsap-mo')
        regenerateUrl = add_query_arg(regenerateUrl, 'dzsap_wave_regenerate', 'on')
        regenerateUrl = add_query_arg(regenerateUrl, 'track_id', _t.attr('data-playerid'))
        regenerateUrl = add_query_arg(regenerateUrl, 'track_url', (_t.attr('data-player-source')));



        _t.after('<iframe class="regenerate-waveform-iframe" src="' + regenerateUrl + '" width="100%" height="540"></iframe>')


        return false;
      }


    }
  }

  function submitPcmData($form_) {

    var $form = jQuery($form_);
    updateFields();


    var trackUrl = $form.find('*[name="wavedata_track_url"]').eq(0).val();
    var trackId = $form.find('*[name="wavedata_track_id"]').eq(0).val();
    var trackUrlId = $form.find('*[name="wavedata_url_id"]').eq(0).val();
    var pcmData = $form.find('*[name="wavedata_pcm"]').eq(0).val();



    var data = {
      action: 'dzsap_submit_pcm',
      playerid: trackId,
      source: trackUrl,
      postdata: pcmData,
      forceUrlTrackId: trackUrlId,

    };



    $.ajax({
      type: "POST",
      url: window.ajaxurl,
      data: data,
      success: function (response) {




      },
      error: function (arg) {
          console.log('Got this from the server: ' + arg, arg);
        ;
      }
    });
  }

  function handle_submit() {

    submitPcmData(this);

    return false;




  }


}



window.dzsap_waveform_autogenerateWithId = function(attachmentId){

  var data = {
    action: 'dzsap_get_pcm',
    source: attachmentId,
  }


  jQuery.ajax({
    type: "POST",
    url: window.ajaxurl,
    data: data,
    success: function (response) {



      if(!response){


        const iframeCode = '<iframe class="regenerate-waveform-iframe" style="opacity: 0; pointer-events: none; position:absolute; bottom:0; right:0; width: 100px; height: 100px;" src="'+window.dzsap_settings.admin_url+'admin.php?page=dzsap-mo&dzsap_wave_regenerate=on&dzsap_wave_generate_auto=on&disable_isShowTrackInfo=off&track_id='+encodeURIComponent(attachmentId)+'" width="100%" height="400"></iframe>';

        jQuery('body').append(iframeCode)
      }
    },
    error: function (arg) {
      if (typeof window.console != "undefined") {
        console.warn('Got this from the server: ' + arg);
      }
      ;
    }
  });
}