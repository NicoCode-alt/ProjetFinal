// Fonction pour le fixed de ma nav_bar :
document.addEventListener("DOMContentLoaded", function(){
  // add padding top to show content behind navbar
  navbar_height = document.querySelector('.navbar').offsetHeight;
  document.body.style.paddingTop = navbar_height + 'px';
}); 

// Fonction pour le scroll de l'image music.png pour le retour en haut de page :
let mybutton = document.getElementById("myBtn");

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
    mybutton.style.display = "block";
  }
  else {
    mybutton.style.display = "none";
  }
}

//Fonctions phrases footer
let texts = [
    "Site breveté et créé ...",
    "par une équipe ...",
    "formée par Webforce 3.",
    "Lavdon,",
    "Nicolas,",
    "Ghislain,",
    "Julien.",
];
let count = 0;
function changeText() {
    $(".typewrite").text(texts[count]);
    count < 7 ? count++ : (count = 0);
}
setInterval(changeText, 2000);