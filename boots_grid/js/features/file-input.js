(function ($) {

  Drupal.behaviors.fileInput = {
    attach: function(context, settings) {
      var $fileInputs = $('input[type="file"]', context);

      $fileInputs.on('change', function() {
        var path = $(this).val();
        var filename = path.replace(/^.*\\/, "");
        $(this).siblings('.value').html(filename);
      });
    }
  };

})(jQuery);
