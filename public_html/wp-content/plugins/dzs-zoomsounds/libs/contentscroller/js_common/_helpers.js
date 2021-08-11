function sanitize_background_url(aux){

  aux = aux.replace('url("', '');
  aux = aux.replace('")', '');
  aux = aux.replace('url(', '');
  aux = aux.replace(')', '');

  return aux;

}

exports.sanitize_background_url = sanitize_background_url;