(function($) {
    $(function() { // DOM prêt
        // // Active / désactive les styles de navigation par clic
        $('#nav-toggle').click(function() {
            $('nav ul').slideToggle();
        });
        // Bascule menu Hamburger en X
        $('#nav-toggle').on('click', function() {
            this.classList.toggle('active');
        });
    }); // fin DOM
})(jQuery);