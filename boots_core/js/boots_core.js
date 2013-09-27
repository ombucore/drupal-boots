(function($) {

  // Override markup for autocomplete dropdown.
  // This is copied and modified from misc/autocomplete.js, which outputs HTML directly (boo!)
  if (Drupal.jsAC) {
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
        //top: parseInt(position.top + this.input.offsetHeight, 10) + 'px',
        //left: parseInt(position.left, 10) + 'px',
        width: $input.innerWidth() + 'px',
        //display: 'none'
      });
      $(this.popup).find('ul.dropdown-menu').css({
        width: $input.innerWidth() + 'px',
      });

      $input.after(this.popup);

      // Do search.
      this.db.owner = this;
      this.db.search(this.input.value);
    };
  
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
  }
})(jQuery);