(function ($) {

  Drupal.behaviors.topLink = {
    attach: function(context, settings) {
      $topLink = $('#top-link a', context);

      // Scroll user to the top of the document on click of the top link.
      $topLink.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, 500);
      });

      // Keep the top link hidden until the user scrolls a reasonable distance
      // down the page.
      var waypoint = new Waypoint({
        element: $('html').get(0),
        handler: function(direction) {
          $('html').toggleClass('show-top-link', (direction == 'down'));
        },
        offset: -200
      });
    }
  };

})(jQuery);
