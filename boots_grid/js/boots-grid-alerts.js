(function ($) {
  Drupal.behaviors.bootsGridAlerts = {
    attach: function(context, settings) {

      // When the user clicks an alert's dismiss link, class it accordingly.
      $('[data-name="console"]').find('.close').on('click', function(e) {
        e.preventDefault();
        var container = $(this).closest('.' + $(this).attr('data-dismiss'));
        // Set the max-height of each alert block to its actual pixel height.
        // By doing this, the accompanying CSS rules will smoothly transition
        // the max-height to 0.
        container.css('max-height', container.innerHeight());
        container[0].offsetHeight; // Chrome repaint.
        container.addClass('animate');
        container[0].offsetHeight; // FF repaint.
        container.addClass('dismissed');
      });
    }
  }
})(jQuery);
