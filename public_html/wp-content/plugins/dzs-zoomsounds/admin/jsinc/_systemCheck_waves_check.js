exports.systemCheck_waves_check = function(){


  var $ = jQuery;

  $(document).on('click','.systemCheck_waves_check-regenerate-waveform', function(e){

    let $t = $(this);




    const iframeCode = '<iframe class="regenerate-waveform-iframe" src="'+window.dzsap_settings.admin_url+'admin.php?page=dzsap-mo&dzsap_wave_regenerate=on&disable_isShowTrackInfo=on&track_url='+encodeURIComponent($t.attr('data-track_url'))+'" width="100%" height="400"></iframe>';

    $t.get(0).outerHTML = iframeCode;

    return false;
  })
  $(document).on('change','#dzsap_is_admin_systemCheck_waves', function(e){

    let $t = $(this);

    const COOKIE_LAB = 'dzsap_is_admin_systemCheck_waves';
    if($t.prop('checked')){
      document.cookie = COOKIE_LAB+"=on";
    }else{

      document.cookie = COOKIE_LAB+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    }
    setTimeout(function(){
      window.location.reload()

    },100);
  })


}