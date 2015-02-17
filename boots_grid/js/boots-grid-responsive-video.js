(function ($) {

  Drupal.behaviors.responsiveVideo = {
    attach: function(context, settings) {
    // Wrap iframe elements inside the content region.
    // TODO: Alter the rendered markup for embedded videos in Drupal.
    $('[data-type="region"][data-name="content"] iframe', context).once('responsive-video').wrap('<div class="video-container"><div class="video-frame"></div></div>');

    }
  };

})(jQuery);
