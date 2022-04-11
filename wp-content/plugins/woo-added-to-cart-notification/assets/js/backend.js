'use strict';

(function($) {
  $(function() {
    wooac_settings();
  });

  $(document).on('change', '[name="wooac_style"]', function() {
    wooac_settings();
  });

  function wooac_settings() {
    var style = $('[name="wooac_style"]').find(':selected').val();

    $('[class^="wooac-show-if-style-"]').hide();
    $('.wooac-show-if-style-' + style).show();
  }
})(jQuery);