//name of file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });

  

// show modal when form add_send 
function fn1(){
    document.getElementById('div1').style.display = "none";
    document.getElementById('div2').style.display = "inline";
}

// rating systems for add_form
var ratings = 0;
$(function () {
    $(".ratings").starrr().on("starrr:change", function (event, value){
        ratings = value;
        $("#save_rating").val(ratings);
    });
});


// show wideo player if use input file
$(document).on("change", ".custom-file-input", function(evt) {
  var $source = $('#video_here');
  $source[0].src = URL.createObjectURL(this.files[0]);
  $source.parent()[0].load();

  var $source2 = $('#video_here_2');
  $source2[0].src = URL.createObjectURL(this.files[0]);
  $source2.parent()[0].load();
});


// get time and change to second times for video
$(document).ready(function() {
  $('#short_time').on('input', function() {

    var hours, minutes, seconds;
    var czas = document.getElementById("short_time").value; 
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

      var change = $("input[name='time_sec']");
      change.val(time_sec);


  });

});

// get time and change to second times for thumbnail
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



//UP BUTTON 
var mybutton = document.getElementById("myBtn");

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 570 || document.documentElement.scrollTop > 570) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

$('.toTop').on('click', function(event) {
  event.preventDefault();
  $('html, body').animate({ scrollTop: 0 }, 'slow');         
});