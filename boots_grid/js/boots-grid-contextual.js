(function ($) {

  // Add an .attrchange function to jQuery.
  // @see https://stackoverflow.com/a/24284069/325018
  var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

  $.fn.attrchange = function(callback) {
      if (MutationObserver) {
          var options = {
              subtree: false,
              attributes: true
          };

          var observer = new MutationObserver(function(mutations) {
              mutations.forEach(function(e) {
                  callback.call(e.target, e.attributeName);
              });
          });

          return this.each(function() {
              observer.observe(this, options);
          });

      }
  };

  Drupal.behaviors.bootsGridContextual = {
    attach: function(context, settings) {

      // Refs #4444: Find every contextual links region on the page and set its
      // min-height to a value that will accommodate the gear menu it contains.
      // It's worth noting that the .contextual* classes are only applied when a
      // Drupal user is signed in, so this will not affect an anonymous visitor.
      if (MutationObserver) {
        setUpChangingMinHeight(context);
      }
      else {
        setUpFixedMinHeight(context);
      }
    }

  };

  function setUpChangingMinHeight(context) {
    $('.contextual-links-region:not(.processed-height)', context)
      .each(function(i, region) {
        var $region = $(region);
        var $wrapper = $region.find('.contextual-links-wrapper');
        var $trigger = $wrapper.find('.contextual-links-trigger');
        var $links = $wrapper.find('.contextual-links');

        var minHeight = $links.outerHeight() + $trigger.outerHeight() + 10;
        var originalMinHeight = parseInt($region.css('min-height')) > 0 ? $region.css('min-height') : '20px';

        $region.css({
          'min-height': originalMinHeight
        });

        $wrapper.attrchange(function(attrName) {
          if (attrName === 'class') {
            if ($wrapper.hasClass('contextual-links-active')) {
              $region.css({
                'min-height': minHeight
              });
            }
            else {
              $region.css({
                'min-height': originalMinHeight
              });
            }
          }
        });
      })
      .addClass('processed-height');
  }

  function setUpFixedMinHeight(context) {
    $('ul.contextual-links', context).each(function(i, el) {

      // If the contextual list we're considering belongs to the system main
      // block, allow its region to collapse to 0 height.
      if ($(el).closest('#block-system-main').length) {
        return true;
      }

      var $gear = $(el).siblings('.contextual-links-trigger');
      var $region = $(el).closest('.contextual-links-region');
      var height = $(el).outerHeight() + $gear.outerHeight() + 10;
      $region.css('min-height', height + 'px');
    });
  }

})(jQuery);
