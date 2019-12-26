(function ($) {
    $(function () {
 // DOM prêt
        // Déclare une constante
        const $navToggle = $('#nav-toggle');
        // // Active / désactive les styles de navigation par clic
        $navToggle.click(function () {
            $('nav ul').slideToggle();
        });
        // Bascule menu Hamburger en X
        $navToggle.on('click', function () {
            this.classList.toggle('active');
        });
    }); // fin DOM
})(jQuery);