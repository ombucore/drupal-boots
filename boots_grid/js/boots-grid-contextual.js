(function ($) {
  Drupal.behaviors.bootsGridContextual = {
    attach: function(context, settings) {

      // Refs #4444: Find every contextual links region on the page and set its
      // min-height to a value that will accommodate the gear menu it contains.
      // It's worth noting that the .contextual* classes are only applied when a
      // Drupal user is signed in, so this will not affect an anonymous visitor.

      $('ul.contextual-links', context).each(function(i, el) {
        var $gear = $(el).siblings('.contextual-links-trigger');
        var $region = $(el).closest('.contextual-links-region');
        var height = $(el).outerHeight() + $gear.outerHeight() + 10;
        $region.css('min-height', height + 'px');
      });
    }
  }
})(jQuery);
