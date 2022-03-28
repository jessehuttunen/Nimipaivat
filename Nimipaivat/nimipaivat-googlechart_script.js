//Creates Google charts on the nameday front page.

google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(Chart_men);
google.charts.setOnLoadCallback(Chart_women);

//Google chart for most popular mens names
function Chart_men() {
  var data = new google.visualization.DataTable(my_variables.names); //Mens data from php
  var options = { //Settings for graph
    title: my_variables.title, //Chart title from php
      hAxis: { minValue: 0,}, //Graph blocks start from value 0, by default it started from about 30K.
    chartArea: { 'width': '65%', 'height': '80%' },  // Chart graph size  
    legend: 'none', //Removes unnecessary info block from chart
  };
  var chart = new google.visualization.BarChart(document.getElementById(my_variables.div_id));

//Click bar to open that names page
google.visualization.events.addListener(chart, 'select', function (e) {
  var selection = chart.getSelection();
      if (selection.length) {
          var row = selection[0].row;
        let name_with_rank = data.getValue(row, 0); 
        let name_without_rank = name_with_rank.substring(name_with_rank.indexOf(' ') + 1);
          location.href = "/info/nimihaku/?fname="+name_without_rank+"&country=fi";
      }
});

  //Draw the google chart of men
  chart.draw(data, options);

  //Draw the chart again when ever the window size changes to make the chart responsive.
  jQuery(document).ready(function ($) {
    $(window).resize(function () {
      chart.draw(data, options);  
    });
    $(window).trigger('resize');
  });
}

//Google chart for most popular womens names
function Chart_women() {
    var data = new google.visualization.DataTable(my_variables.names2); //Womens data from php
    var options = { //Settings for graph
      title: my_variables.title2, //Chart title from php
      hAxis: {minValue: 0}, //Graph blocks start from value 0, by default it started from about 30K.
      chartArea: { 'width': '65%', 'height': '80%' }, // Chart graph size
      legend: 'none', //Removes unnecessary info block from chart
    };
    var chart = new google.visualization.BarChart(document.getElementById(my_variables.div_id2));
    
  //Click bar to open that names page
  google.visualization.events.addListener(chart, 'select', function (e) {
      var selection = chart.getSelection();
          if (selection.length) {
              var row = selection[0].row;
            let name_with_rank = data.getValue(row, 0); 
            let name_without_rank = name_with_rank.substring(name_with_rank.indexOf(' ') + 1);
           location.href = "/info/nimihaku/?fname=" + name_without_rank + "&country=fi";
          }
  });  
  
  //Draw the google chart of women
  chart.draw(data, options);  

  //Draw the chart again when ever the window size changes to make the chart responsive.
  jQuery(document).ready(function ($) {
    $(window).resize(function () {
      chart.draw(data, options);  
    });
    $(window).trigger('resize');
  });

}
