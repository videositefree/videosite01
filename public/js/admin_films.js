
// show wideo player if use input file
$(document).on("change", ".custom-file-input", function(evt) {
  var $source = $('#video_here');
  $source[0].src = URL.createObjectURL(this.files[0]);
  $source.parent()[0].load();
});

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
        time_sec = "0"
      }

      var change = $("input[name='time_start']");
      change.val(time_sec);


  });

});

// get time and change to second times for thumbnail
$(document).ready(function() {
  $('#end').on('input', function() {

    var hours, minutes, seconds;
    var czas = document.getElementById("end").value; 
      [hours, minutes, seconds] = czas.split(':');
      console.log (hours, minutes, seconds);

      if(seconds == null || seconds == '' || seconds == undefined) {
        seconds = 00;

     }

      var time_sec;
      time_sec = hours * (60 * 60) + minutes * 60 + seconds * 1;
      console.log (time_sec);

      if(isNaN(time_sec)){
        time_sec = "0"
      }

      var change = $("input[name='time_end']");
      change.val(time_sec);


  });

});
