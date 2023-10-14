(function($) {
    $('.display-user-nav1').on('click', function(event) {
        $('.ativo').addClass('inativo').removeClass('ativo');
        $(this).removeClass('inativo').addClass('ativo');
    });
})(jQuery);