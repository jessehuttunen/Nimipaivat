//Countdown timers original source: https://stackoverflow.com/questions/41464114/multiple-countdown-timer

// Some German names have several namedays per year, so it was neccessary to bring namedays in a array to create a countdown timers for each day.
var namedays = my_variable.namedays;
namedays.forEach(myFunction);
function myFunction(item, index) {
    if (index != 0) {
        timedown(item, "countdown" + index);
    }
}

//Timer for Swedish finnish namedays.
var end2 = my_variable.year2 + "/" + my_variable.month2 + "/" + my_variable.day2 + " 00:00 AM";
if (my_variable.year2) {
    timedown(end2, "countdown_swe");
}

//Cretes a countdown timer
function timedown(ti,id){
// Set the date we're counting down to
var countDownDate = new Date(ti).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
    if (document.getElementById(id) != null) {
    document.getElementById(id).innerHTML = (days!=0 ? days + "pv " : '') + (hours!= 0 ? hours + "t " : '')
    + (minutes != 0 ? minutes + "min " : '') + seconds + "s ";
}
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        if (document.getElementById(id) != null) {
            document.getElementById(id).innerHTML = "Nimipäivä on tänään.";
        }
    }
}, 1000);
}

