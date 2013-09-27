(function($) {

  // Override vertical tab's `focus()` method to trigger tinyMCE resize.
  // Stash the original method.
  if (Drupal.verticalTab) {
    Drupal.verticalTab.prototype.superFocus = Drupal.verticalTab.prototype.focus;
    // Call the original method and trigger tinyMCE resize.
    Drupal.verticalTab.prototype.focus = function() {
      this.superFocus();
      var editors = $(this.fieldset).find('.mceEditor');
      $.each(editors, function() {
        var instance = tinyMCE.get($(this).attr('id').replace('_parent', ''));
  
        // Add a min height if it doesn't have one yet.
        if (instance.plugins.autoresize && instance.plugins.autoresize.autoresize_min_height < 50) {
            instance.plugins.autoresize.autoresize_min_height = 50;
        }
        // Trigger the resize
        instance.execCommand('mceAutoResize');
      });
    };
  }

  // OnReady.
  jQuery(document).ready(function() {
      //$('.disable-on-click').click( function(e) {
      jQuery('input[type=submit]').click( function(e) {
          var th = jQuery(this),
              $form = jQuery(this.form);
          $form.append('<input type="hidden" name="' + th.attr('name') + '" value="' + th.val() + '" />');
          th.attr('value', 'Please wait...');
          th.addClass('disabled');
          // Need to submit manually on Safari
          $form.submit();
      });
  });

})(jQuery);
