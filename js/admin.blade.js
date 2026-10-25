//name of file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


// script for chceck checbox if = 1
$(function(){
$('.preference').each(function(e){
    if($(this).val() == 1){
        $(this).attr("checked", "checked");
    }
});
});


// get time and change to second times for video
$(document).ready(function() {
    $('#short_time_video').on('input', function() {
  
      var hours, minutes, seconds;
      var czas = document.getElementById("short_time_video").value; 
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
  
        var change = $("input[name='time_sec_video']");
        change.val(time_sec);
  
  
    });
  
});


// get time and change to second times for video
$(document).ready(function() {
    $('#short_time_thumbnail').on('input', function() {
  
      var hours, minutes, seconds;
      var czas = document.getElementById("short_time_thumbnail").value; 
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
  
        var change = $("input[name='time_sec_thumbnail']");
        change.val(time_sec);
  
  
    });
  
});

// dark mode switch — przeniesione do marquee-preview.js (jeden spójny system
// dla strony publicznej i panelu admina, obsługuje oba przełączniki
// desktop/mobile i localStorage bez konfliktu o ten sam klucz)


// tooltips 
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })


// show poster or video 
var figure = $(".video-wrapper").hover( hoverVideo, hideVideo );
function hoverVideo(e) {  
$('video', this).get(0).play(); 
}
function hideVideo(e) {
$('video', this).get(0).load(); 
}




// przycisk "do góry" — przeniesiony do marquee-preview.js (jeden spójny
// system, zgodny z klasami CSS zamiast inline style="display")



// show wideo poster if use input file
$(document).on("change", ".custom-file-input", function(evt) {
  var $source = $('#video_here');
  $source[0].src = URL.createObjectURL(this.files[0]);
  $source.parent()[0].load();

  var $source2 = $('#video_here_2');
  $source2[0].src = URL.createObjectURL(this.files[0]);
  $source2.parent()[0].load();
});














// Use this in join films — 5 identycznych pol wyboru pliku (join_films.blade.php)
for (var i = 1; i <= 5; i++) {
  (function (n) {
    // pokaz nazwe wybranego pliku
    $(".custom-file-input-" + n).on("change", function () {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings("#film_" + n).addClass("selected").html(fileName);
    });

    // pokaz podglad wideo po wybraniu pliku
    $(function () {
      $("#film_" + n).on('change', function (evt) {
        var $source = $('#video_here-' + n);
        $source[0].src = URL.createObjectURL(this.files[0]);
        $source.parent()[0].load();
      });
    });
  })(i);
}


