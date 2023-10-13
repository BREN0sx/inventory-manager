(function($) {
    /* ADD */
    $('.display-user-nav1').on('click', function(event) {

        $('.ativo').addClass('inativo').removeClass('ativo');

        /* $('.display-user-nav1').removeClass('ativo inativo'); */

        $(this).removeClass('inativo').addClass('ativo');
        /* $(this).next('.display-user-nav1').addClass('inativo'); */



        /* $('.ativo').toggleClass('ativo inativo');
        $(this).toggleClass('inativo ativo'); */
        /* event.preventDefault(); */
    });

    /* $('.ativo').on('click', function(event) {
        $('.inativo').toggleClass('inativo ativo');
        $(this).toggleClass('ativo inativo');
        event.preventDefault();
    }); */

    /* $('._modal_add_close').on('click', function(event) {
        let modal = document.getElementById('modal_add');
        modal.style.cssText = 'display: none;'
        event.preventDefault();
    }); */
})(jQuery);