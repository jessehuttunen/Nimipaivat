(function($) {

/*** AUTOMATIC BANNER HEIGHT ***/
$(document).ready(function(){
    $('.kiitos-container').css('height', $(window).height());
    $('.not-found-container').css('height', $(window).height());
});
$(window).resize(function(){
    $('.kiitos-container').css('height', $(window).height());
    $('.not-found-container').css('height', $(window).height());
});

$(document).ready(function(){


function updateTime(){
    var currentTime = new Date()
    var hours = currentTime.getHours()
    var minutes = currentTime.getMinutes()
    if (minutes < 10){
        minutes = "0" + minutes
    }
    var t_str = hours + ":" + minutes;
    
    document.getElementById('time').innerHTML = t_str;
}
setInterval(updateTime, 1000);


/*** MENU ANIMATION ***/
$("#nav-icon").click(function(){
  $(this).toggleClass("open");
  $("#myNav").toggleClass("open-menu");
});

$(".overlay a").click(function(){
  $("#myNav").toggleClass("open-menu");
  $("#nav-icon").toggleClass("open");
});

/*** SCROLL TOP ***/
var btn = $('#scroll-top');

$(window).scroll(function() {
  if ($(window).scrollTop() > 600) {
    btn.addClass('show-scroll-button');
  } else {
    btn.removeClass('show-scroll-button');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});

}); //DOCUMENT READY


})( jQuery );







