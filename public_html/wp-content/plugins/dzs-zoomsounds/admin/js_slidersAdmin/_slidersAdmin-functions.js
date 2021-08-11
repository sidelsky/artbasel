export function init_generateSampleShortcode(selfInstance) {


  function handleChangeInTargetFields() {

    var slug = '';

    slug = jQuery('#slug').val();


    var sampleShortcode = selfInstance.SA_CONFIG.shortcode_sample;

    sampleShortcode = sampleShortcode.replace(/{{theslug}}/g, slug);



    jQuery('.dzssa--sample-shortcode-area--readonly').text(sampleShortcode);

  }

  jQuery('#slug').on('change', handleChangeInTargetFields);

  handleChangeInTargetFields();
}

export function prepare_send_queue_calls(customdelay, selfInstance) {


  var delay;
  if (typeof customdelay == 'undefined') {
    delay = 2000;
  } else {
    delay = customdelay;
  }


  selfInstance.isSaving = true;
  clearTimeout(selfInstance.inter_send_to_ajax);
  selfInstance.inter_send_to_ajax = setTimeout(selfInstance.send_queue_calls, delay);
}


export function getSliderItemContainerFromSettingField($t) {

  let $sliderItem = null;
  if ($t.parent().parent().parent().parent().hasClass('slider-item')) {
    $sliderItem = $t.parent().parent().parent().parent();
  }
  if ($t.parent().parent().parent().parent().parent().hasClass('slider-item')) {
    $sliderItem = $t.parent().parent().parent().parent().parent();
  }

  if ($t.parent().parent().parent().parent().parent().parent().hasClass('slider-item')) {
    $sliderItem = $t.parent().parent().parent().parent().parent().parent();
  }
  if ($t.parent().parent().parent().parent().parent().parent().parent().hasClass('slider-item')) {
    $sliderItem = $t.parent().parent().parent().parent().parent().parent().parent();
  }
  if ($t.parent().parent().parent().parent().parent().parent().parent().parent().hasClass('slider-item')) {
    $sliderItem = $t.parent().parent().parent().parent().parent().parent().parent().parent();
  }

  if ($t.parent().parent().parent().parent().parent().parent().parent().parent().parent().hasClass('slider-item')) {
    $sliderItem = $t.parent().parent().parent().parent().parent().parent().parent().parent().parent();
  }

  return $sliderItem;
}