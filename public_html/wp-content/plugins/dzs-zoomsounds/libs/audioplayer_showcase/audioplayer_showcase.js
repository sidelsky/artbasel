"use strict";

jQuery(document).ready(function ($) {

  $(document).on('click', '.stats-btn', handle_mouse);

  function handle_mouse(e) {

    var _t = $(this);
    if (_t.hasClass('stats-btn')) {


      var _con = _t.parent();

      if (_t.hasClass('disabled')) {
        return false;
      }
      _t.addClass('disabled');
      setTimeout(function () {
        _t.removeClass('disabled')
      }, 2000)

      if (_con.find('.stats-container').length) {

        _t.removeClass('active');
        _con.find('.stats-container').each(function () {
          var _t2 = $(this);
          _t2.addClass('transitioning-out').removeClass('loaded');







          _t2.slideUp("fast");


          setTimeout(function () {
            _con.find('.stats-container.transitioning-out').remove()
          }, 400)
        })
      } else {

        _t.addClass('active');
        load_statistics(_con);
      }

    }
  }


  function load_statistics(_con) {


    if (window.google && window.google.charts) {


      if (window.google.visualization) {


        var _statsBtn = _con.find('.stats-btn').eq(0);
        console.info("NOW APPLYING", _statsBtn.eq(0).attr('data-playerid'));

        var data = {
          action: 'dzsvg_ajax_get_statistics_html',
          url: _statsBtn.attr('data-url'),
          postdata: _statsBtn.attr('data-playerid')
        };

        if (_statsBtn.attr('data-sanitized_source')) {
          data.sanitized_source = _statsBtn.attr('data-sanitized_source');
        }


        $.ajax({
          type: "POST",
          url: window.dzsap_settings.dzsap_site_url + '/?dzsap_action=load_charts_html',
          data: data,
          success: function (response) {
            if (typeof window.console != "undefined") {
              console.groupCollapsed('Submit message Got this from the server:');
              console.log(' ' + response);
              console.groupEnd();
            }


            _con.append('<div class="stats-container">' + response + '</div>')

            setTimeout(function () {

              var _c = _con.find('.stats-container');






              _c.addClass('loaded');


              var auxr = /<div class="hidden-data">(.*?)<\/div>/g;
              var aux = auxr.exec(response);


              var aux_resp = '';
              if (aux[1]) {
                aux_resp = aux[1];
              }


              var resp_arr = [];


              try {
                resp_arr = JSON.parse(aux_resp);
              } catch (err) {

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

                    val4 = parseFloat(val4);
                  }


                  if (isNaN(val4) == false) {
                    resp_arr['lastdays'][i][j] = val4;
                  }
                  arr[i + 1].push(resp_arr['lastdays'][i][j]);
                }

              }



              var data = google.visualization.arrayToDataTable(arr);

              var options = {

                backgroundColor: '#444444'
                , height: '300'
                , legend: {position: 'top', maxLines: 1}
                , chart: {
                  title: 'Track Performance'
                  , backgroundColor: '#444444'
                }
                , chartArea: {
                  backgroundColor: '#444444'
                }
                , tooltip: {isHtml: true}
              };



              var chart = new google.visualization.AreaChart(_con.find('.trackchart').get(0));
              chart.draw(data, options);


              auxr = /<div class="hidden-data-time-watched">(.*?)<\/div>/g;

              aux = auxr.exec(response);


              aux_resp = '';
              if (aux[1]) {
                aux_resp = aux[1];
              }


              resp_arr = [];


              try {
                resp_arr = JSON.parse(aux_resp);
              } catch (err) {

              }



              arr = [];


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

                    val4 = parseInt((parseFloat(val4) / 60), 10);
                  }


                  if (isNaN(val4) == false) {
                    resp_arr['lastdays'][i][j] = val4;
                  }
                  arr[i + 1].push(resp_arr['lastdays'][i][j]);
                }

              }



              data = google.visualization.arrayToDataTable(arr);

              options = {

                color: '#bcb36b'
                , colors: ['#e0d365', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
                , backgroundColor: '#444444'
                , height: '300'
                , legend: {position: 'top', maxLines: 3}
                , bar: {groupWidth: "70%"}
                , chart: {
                  title: 'Track Performance'
                  , backgroundColor: '#444444'
                }
                , chartArea: {
                  backgroundColor: '#444444'
                }
                , tooltip: {isHtml: true}
              };







              var chart2 = new google.visualization.ColumnChart(_con.find('.trackchart-time-watched').get(0));
              chart2.draw(data, options);


              auxr = /<div class="hidden-data-month-viewed">(.*?)<\/div>/g;

              aux = auxr.exec(response);


              aux_resp = '';
              if (aux[1]) {
                aux_resp = aux[1];
              }


              resp_arr = [];


              try {
                resp_arr = JSON.parse(aux_resp);
              } catch (err) {

              }



              arr = [];


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

                    val4 = parseFloat(val4);
                  }


                  if (isNaN(val4) == false) {
                    resp_arr['lastdays'][i][j] = val4;
                  }
                  arr[i + 1].push(resp_arr['lastdays'][i][j]);
                }

              }



              data = google.visualization.arrayToDataTable(arr);

              options = {

                color: '#bcb36b'
                , colors: ['#66a4e0', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
                , backgroundColor: '#444444'
                , height: '300'
                , legend: {position: 'top', maxLines: 3}
                , bar: {groupWidth: "70%"}
                , chart: {
                  title: 'Track Performance'
                  , backgroundColor: '#444444'
                }
                , chartArea: {
                  backgroundColor: '#444444'
                }
                , tooltip: {isHtml: true}
              };







              var chart3 = new google.visualization.ColumnChart(_con.find('.trackchart-month-viewed').get(0));
              chart3.draw(data, options);


              _c.slideDown("fast");

              setTimeout(function () {

                $(this).css('height', 'auto');
              }, 400);










            }, 100);


          },
          error: function (arg) {
            if (typeof window.console != "undefined") {
              console.log('Got this from the server: ' + arg, arg);
            }
            ;

          }
        });


      } else {
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(function () {
          load_statistics(_con);
        });
      }


    } else {

      if (window.dzsvg_loading_google_charts) {


      } else {


        var url = 'https://www.gstatic.com/charts/loader.js';




        $.ajax({
          url: url,
          dataType: "script",
          success: function (arg) {





            console.info('loaded charts');


          }
        });


        window.dzsvg_loading_google_charts = true;
      }

      setTimeout(function () {
        load_statistics(_con)
      }, 1000);
    }

  }

})

