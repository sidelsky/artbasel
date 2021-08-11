'use strict';
jQuery(document).ready(function($){

  google.load("visualization", "1.0", {"packages": ["corechart"]});


  google.setOnLoadCallback(dzsap_drawChart);


  function dzsap_drawChart() {

    let dataArr = JSON.parse($('.dzsap-admin-feed--dashboard-data').eq(0).text());

    var data = new google.visualization.DataTable();
    data.addColumn("string", "Topping");
    data.addColumn("number", "Slices");
    data.addRows(dataArr);
  var options = {
      "title": "' . esc_html__('Number of Comments', DZSAP_ID) . '",
        "width": "100%",
        "height": 300
    };
  var chart = new google.visualization.PieChart(document.getElementById("dzsap_chart_div"));
  chart.draw(data, options);
  }
})