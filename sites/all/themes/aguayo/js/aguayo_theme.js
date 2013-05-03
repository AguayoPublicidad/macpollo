
(function($){
  Drupal.behaviors.aguayo = {
    attach:function(context, settings) {
      $("#messages-wrapper #messages-toggle").once('messages', function(context, settings) {
        $(this).click(function(e) {
          e.preventDefault();
          $('#messages-content').toggle();
        });
      });
    }
  }
}(jQuery));
