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

// dark mode switch
const toggleSwitch = document.querySelector('.mode-switch input[type="checkbox"]');
const currentTheme = localStorage.getItem('theme');

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);
  
    if (currentTheme === 'dark') {
        toggleSwitch.checked = true;
    }
}

function switchTheme(e) {
    if (e.target.checked) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }
    else {        document.documentElement.setAttribute('data-theme', 'light');
          localStorage.setItem('theme', 'light');
    }    
}

toggleSwitch.addEventListener('change', switchTheme, false);


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




//UP BUTTON
if(document.getElementById('myBtn')){
var mybutton = document.getElementById("myBtn");

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 450 || document.documentElement.scrollTop > 450) {
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
}



// show wideo poster if use input file
$(document).on("change", ".custom-file-input", function(evt) {
  var $source = $('#video_here');
  $source[0].src = URL.createObjectURL(this.files[0]);
  $source.parent()[0].load();

  var $source2 = $('#video_here_2');
  $source2[0].src = URL.createObjectURL(this.files[0]);
  $source2.parent()[0].load();
});














// Use this in join films

//name of file appear on select
$(".custom-file-input-1").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings("#film_1").addClass("selected").html(fileName);
});

//name of file appear on select
$(".custom-file-input-2").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings("#film_2").addClass("selected").html(fileName);
});

//name of file appear on select
$(".custom-file-input-3").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings("#film_3").addClass("selected").html(fileName);
});

//name of file appear on select
$(".custom-file-input-4").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings("#film_4").addClass("selected").html(fileName);
});

//name of file appear on select
$(".custom-file-input-5").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings("#film_5").addClass("selected").html(fileName);
});






// show wideo poster if use input file
$(function(){
  $("#film_1").on('change', function(evt){
    var $source = $('#video_here-1');
    $source[0].src = URL.createObjectURL(this.files[0]);
    $source.parent()[0].load();
  })
   
});


$(function(){
  $("#film_2").on('change', function(evt){
    var $source = $('#video_here-2');
    $source[0].src = URL.createObjectURL(this.files[0]);
    $source.parent()[0].load();
  })
   
});


$(function(){
  $("#film_3").on('change', function(evt){
    var $source = $('#video_here-3');
    $source[0].src = URL.createObjectURL(this.files[0]);
    $source.parent()[0].load();
  })
   
});

$(function(){
  $("#film_4").on('change', function(evt){
    var $source = $('#video_here-4');
    $source[0].src = URL.createObjectURL(this.files[0]);
    $source.parent()[0].load();
  })
   
});

$(function(){
  $("#film_5").on('change', function(evt){
    var $source = $('#video_here-5');
    $source[0].src = URL.createObjectURL(this.files[0]);
    $source.parent()[0].load();
  })
   
});


