// get time and change to second times for video
$(document).ready(function() {
  $('#start').on('input', function() {

    var hours, minutes, seconds;
    var czas = document.getElementById("start").value; 
      [hours, minutes, seconds] = czas.split(':');
      console.log (hours, minutes, seconds);

      if(seconds == null || seconds == '' || seconds == undefined) {
        seconds = 00;

     }
     
      var time_sec;
      time_sec = hours * (60 * 60) + minutes * 60 + seconds * 1;
      console.log (time_sec);

      if(isNaN(time_sec)){
        time_sec = "720"
      }

      var change = $("input[name='time_start']");
      change.val(time_sec);


  });

});