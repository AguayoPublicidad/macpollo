(function ($) {
  Drupal.behaviors.tottoInfoShopMenu = {
    attach: function (context, settings) {
      $('.block-totto-infoshop').once('localizar-tienda-info', function() {
        var bgDiv = $('<div>').addClass('totto-infoshop-bg');
        $('.block-totto-infoshop').parents('.section-wrapper').before(bgDiv);

        $('.block-totto-infoshop').addClass('element-invisible'); //hide();
        $('.totto-infoshop-bg').hide();

        $('.localizar-tienda').click(function(e){
          e.preventDefault();
          if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.block-totto-infoshop').removeClass('element-invisible').hide("slow");
            $('.totto-infoshop-bg').hide("slow");
          }
          else {
            $(this).addClass('active');
            // Gmaps cargaba mal cuando se ocultaba con display none
            // Le pusimos la clase element-invisible aliniciar y acá le ponemos un hide
            // para asegurar que los efecos se vean bien.
            $('.block-totto-infoshop').hide();
            $('.block-totto-infoshop').removeClass('element-invisible').show("slow");
            $('.totto-infoshop-bg').show("slow");
          }
        });
      });
      $('.totto-infoshop-close a').once('localizar-tienda-info', function() {
        $(this).click(function(e) {
          e.preventDefault();
          $('.block-totto-infoshop').hide('slow');
          $('.totto-infoshop-bg').toggle("slow");
          $('.localizar-tienda').removeClass('active');
        });
      });
    }
  }
})(jQuery)


