// Vérifie la distance au sommet et affiche de haut en bas "back-to-top".
$(window).scroll(function () {
    if ( $(this).scrollTop() > 800 ) {
        $('.back-to-top').addClass('show-back-to-top');
    } else {
        $('.back-to-top').removeClass('show-back-to-top');
    }
});

// Clique sur l'événement pour faire défiler vers le haut
$('.back-to-top').click(function () {
    $('html, body').animate({ scrollTop : 0 }, 800);
    return false;
});