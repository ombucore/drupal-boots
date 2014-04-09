(function ($) {
  Drupal.behaviors.bootsGridMenus = {
    attach: function(context, settings) {

      // When the user clicks a submenu link, toggle an open class on its
      // parent list item.
      $('.submenu-toggle > a', context).on('click', function(e) {
        e.preventDefault();
        $(this).closest('li').toggleClass('open');
      });
    }
  }
})(jQuery);
