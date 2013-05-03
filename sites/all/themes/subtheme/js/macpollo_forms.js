(function($) {

  Drupal.behaviors.macPolloForms = {
    attach: function(context, settings) {
      // Add 'external' CSS class to all external links
      $('form').once('macpollo-form-att', function() {
        $(this).find('.form-type-radio').each(function(index) {
          if ($(this).find('input').attr('checked')) {
            $(this).addClass('active-r');
          }
          // Click.
          // $(this).click(function(e){
          //   $(this).addClass('active-radio');            
          // });

          $('input:radio').click(function() {
              if ($(this).is(':checked')) {
                $(this).parent().addClass('active-r');
              }
              else {
                $(this).parent().removeClass('active-r');
              };
          });

        });
      });
    }
  }
}(jQuery));
