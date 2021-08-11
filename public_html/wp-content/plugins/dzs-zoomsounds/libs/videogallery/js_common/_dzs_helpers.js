exports.formatTime = function (arg) {

  //formats the time
  var s = Math.round(arg);
  var m = 0;
  if (s > 0) {
    while (s > 59) {
      m++;
      s -= 60;
    }
    return String((m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s);
  } else {
    return "00:00";
  }
}

exports.sanitizeToCssPx = (arg)=>{

  if (String(arg).indexOf('%') > -1 || String(arg).indexOf('em') > -1 || String(arg).indexOf('px') > -1 || String(arg).indexOf('auto') > -1) {
    return arg;
  }
  return arg+'px';
}


exports.format_to_seconds = (arg) => {

  var argsplit = String(arg).split(':');
  argsplit.reverse();
  var secs = 0;

  if (argsplit[0]) {
    argsplit[0] = String(argsplit[0]).replace(',', '.');
    secs += Number(argsplit[0]);
  }
  if (argsplit[1]) {
    secs += Number(argsplit[1]) * 60;
  }
  if (argsplit[2]) {
    secs += Number(argsplit[2]) * 60;
  }


  return secs;
}