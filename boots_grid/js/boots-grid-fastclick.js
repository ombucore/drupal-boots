(function ($) {
  Drupal.behaviors.bootsGridFastClick = {
    attach: function(context, settings) {

      // Instantiate fastclick for speedy tap response on touch devices.
      this.fc = FastClick.attach(document.body);
    }
  }
})(jQuery);
