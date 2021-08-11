export function init_query_arg_globals(){

  window.get_query_arg = function (purl, key){



    if (purl.indexOf(key + '=') > -1) {

      var regexS = "[?&]" + key + "(.+?)(?=&|$)";
      var regex = new RegExp(regexS);
      var regtest = regex.exec(purl);



      if (regtest != null) {



        if (regtest[1]) {
          var aux = regtest[1].replace(/=/g, '');
          return aux;
        } else {
          return '';
        }


      }

    }
  }



  window.add_query_arg = function(purl, key,value){
    key = encodeURIComponent(key); value = encodeURIComponent(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

    s = s.replace(r,"$1"+pair);

    if(s.indexOf(key + '=')>-1){


    }else{
      if(s.indexOf('?')>-1){
        s+='&'+pair;
      }else{
        s+='?'+pair;
      }
    }


    return s;
  }
}
