// dark mode switch
const toggleSwitchh = document.querySelector('.mode-switch input[type="checkbox"]');
const currentTheme = localStorage.getItem('theme');

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);
  
    if (currentTheme === 'dark') {
        toggleSwitchh.checked = true;
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

toggleSwitchh.addEventListener('change', switchTheme, false);


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
  if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {
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