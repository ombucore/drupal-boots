(function ($) {
  Drupal.behaviors.bootsGridAlerts = {
    attach: function(context, settings) {

      // Set the max-height of each alert block to its actual pixel height,
      // now that it has been rendered.  By doing this, the accompanying CSS
      // rules will smoothly transition the max-height to 0 when the user
      // clicks the dismiss link.
      $('[data-name="console"]').find('.alert-block').each(function (i, e) {
        $(e).css('max-height', $(e).innerHeight());
      });

      // When the user clicks an alert's dismiss link, class it accordingly.
      $('[data-name="console"]').find('.dismiss').on('click', function(e) {
        e.preventDefault();
        $(this).closest($(this).attr('data-selector')).addClass('dismissed');
      });
    }
  }
})(jQuery);
