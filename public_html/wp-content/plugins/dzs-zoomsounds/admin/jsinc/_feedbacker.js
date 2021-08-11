var _feedbacker = null;
var feedbacker_init = () => {

  var $ = jQuery;
  _feedbacker = $('.feedbacker');
  _feedbacker.fadeOut('fast');
}
var feedbacker_show_message = (arg) => {

  _feedbacker.html(arg);
  _feedbacker.fadeIn('fast').delay(2000).fadeOut('fast');
}

exports.feedbacker_init = feedbacker_init;
exports.feedbacker_show_message = feedbacker_show_message;