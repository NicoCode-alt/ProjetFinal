// Fonction pour le scroll de ma nav_bar :
$(document).ready(function () {
    $(window).bind("scroll", function () {
        let navHeight = $(".header").height();
        if ($(window).scrollTop() > navHeight) {
            $(".navbar").addClass("fixed");
        } else {
            $(".navbar").removeClass("fixed");
        }
    });
});

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
