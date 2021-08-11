'use strict';

jQuery(document).ready(function($){





  google.charts.load('current', {packages: ['corechart', 'bar', 'geochart']});
  google.charts.setOnLoadCallback(dzsap_drawAnnotations);


  function parse_arr_to_google_charts_data(resp_arr, pargs) {


    var margs = {
      target_attribute: 'name'
      , multiplier: 1
    }


    if (pargs) {
      margs = jQuery.extend(margs, pargs);
    }
    var arr = [];


    arr[0] = [];
    for (var i in resp_arr['labels']) {


      arr[0].push(resp_arr['labels'][i]);
    }
    for (var i in resp_arr['lastdays']) {


      i = parseInt(i, 10);

      arr[i + 1] = [];
      for (var j in resp_arr['lastdays'][i]) {

        j = parseInt(j, 10);


        var val4 = (resp_arr['lastdays'][i][j]);

        if (j != 0) {

          val4 = parseInt(parseFloat(val4) * margs.multiplier);
        }


        if (isNaN(val4) == false) {
          resp_arr['lastdays'][i][j] = val4;
        }
        arr[i + 1].push(resp_arr['lastdays'][i][j]);
      }

    }

    return arr;
  }

  function dzsap_drawAnnotations() {

    var $ = jQuery;





  var auxr = /<div class="dzsap-analytics-hidden-data-general".*?>(.*?)<\/div>/g;
    var aux = auxr.exec($('body').html());


    var aux_resp = '';
    if (aux[1]) {
      aux_resp = aux[1];
    }


    var resp_arr = [];


    try {
      resp_arr = JSON.parse(aux_resp);
    } catch (err) {

    }


    var arr = parse_arr_to_google_charts_data(resp_arr);


    var data = google.visualization.arrayToDataTable(arr);


    var options = {
      title: '',
      annotations: {
        alwaysOutside: true,
        textStyle: {
          fontSize: 14,
          color: '#222',
          auraColor: 'none'
        }
      },
      hAxis: {
        title: 'Date',
        format: 'Y-m-d'
      },
      vAxis: {
        title: 'Plays and likes'
      }
    };


    var chart = new google.visualization.ColumnChart(document.getElementById('dzsap_chart_div'));
    chart.draw(data, options);


    var auxr = /<div class="dzsap-analytics-hidden-data-timewatched".*?>(.*?)<\/div>/g;
    var aux = auxr.exec($('body').html());


    var aux_resp = '';
    if (aux[1]) {
      aux_resp = aux[1];
    }


    var resp_arr = [];


    try {
      resp_arr = JSON.parse(aux_resp);
    } catch (err) {

    }


    arr = parse_arr_to_google_charts_data(resp_arr, {
      multiplier: 1 / 60
    });


    data = google.visualization.arrayToDataTable(arr);


    options = {
      title: '',
      annotations: {
        alwaysOutside: true,
        textStyle: {
          fontSize: 14,
          color: '#222',
          auraColor: 'none'
        }
      },
      colors: ['#e0cd5f', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
      , hAxis: {
        title: 'Date',
        format: 'Y-m-d'
      },
      vAxis: {
        title: 'Downloads'
      }
    };


    var chart2 = new google.visualization.ColumnChart(document.getElementById('dzsap_chart_div-timewatched'));
    chart2.draw(data, options);


    return false;


  }


})