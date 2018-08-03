(function($) {

  /**
  * Override markup for autocomplete dropdown.
  * This is copied and modified from misc/autocomplete.js, which outputs HTML directly (boo!)
  * @TODO: Switch to bootstrap-typeahead.js searching instead
  */
  if (Drupal.jsAC) {

    // Draw the popup
    // Reason to override: change markup to match Bootstrap dropdown
    Drupal.jsAC.prototype.populatePopup = function () {
      var $input = $(this.input);
      var position = $input.position();
      // Show popup.
      if (this.popup) {
        $(this.popup).remove();
      }
      this.selected = false;
      this.popup = $('<div class="dropdown"></div>')[0];
      this.popup.owner = this;
      $(this.popup).css({
        width: $input.innerWidth() + 'px'
      });
      $(this.popup).find('ul.dropdown-menu').css({
        width: $input.innerWidth() + 'px'
      });

      $input.after(this.popup);

      // Do search.
      this.db.owner = this;
      this.db.search(this.input.value);
    };

    // 'Found' function (creates popup menu)
    // Reason to override: change markup to match Bootstrap dropdown
    Drupal.jsAC.prototype.found = function (matches) {
      // If no value in the textfield, do not show the popup.
      if (!this.input.value.length) {
        return false;
      }

      // Prepare matches.
      var ul = $('<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu"></ul>');
      var ac = this;
      for (key in matches) {
        $('<li></li>')
          .html($('<a tabindex="-1" href="#"></a>').html(matches[key]))
          .mousedown(function () { ac.select(this); return false; })
          .mouseover(function () { ac.highlight(this); })
          .mouseout(function () { ac.unhighlight(this); })
          .data('autocompleteValue', key)
          .data('autocompleteValueTitle', matches[key])
          .appendTo(ul);
      }
      // Show popup with matches, if any.
      if (this.popup) {
        if (ul.children().length) {
          $(this.popup).empty().append(ul);
          ul.show();
          $(this.ariaLive).html(Drupal.t('Autocomplete popup'));
        }
        else {
          $(this.popup).css({ visibility: 'hidden' });
          this.hidePopup();
        }
      }
    };

    // Highlighter
    // Reason to override: use class 'active' instead of 'selected'
    Drupal.jsAC.prototype.highlight = function (node) {
      if (this.selected) {
        $(this.selected).removeClass('active');
        $(this.selected).blur();
      }
      $(node).addClass('active');
      this.selected = node;
      $(this.ariaLive).html($(this.selected).html());
    };

  }
})(jQuery);