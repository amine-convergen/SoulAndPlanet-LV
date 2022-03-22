'use strict';

var is_rtl = wooac_vars.is_rtl === '1';

(function($) {
  $(function() {
    if (wooac_vars.style === 'notiny') {
      $.notiny.addTheme('wooac', {
        notification_class: 'notiny-theme-wooac',
      });
    }
  });

  $(document.body).on('added_to_cart', function(e) {
    if (wooac_vars.style === 'default') {
      jQuery('.wooac-popup').magnificPopup('close');
    }

    setTimeout(function() {
      wooac_show();
    }, parseInt(wooac_vars.delay));
  });

  $(document).on('click touch', '#wooac-continue', function(e) {
    var url = $(this).attr('data-url');

    $.magnificPopup.close();

    if (url != '') {
      window.location.href = url;
    }

    e.preventDefault();
  });

  $(document).on('click touch', '.wooac-popup .woosq-btn', function(e) {
    $.magnificPopup.close();

    e.preventDefault();
  });
})(jQuery);

function wooac_show() {
  if (wooac_vars.style === 'notiny') {
    var notiny_image = jQuery('.wooac-notiny img').attr('src');
    var notiny_text = jQuery('.wooac-notiny').text();

    jQuery.notiny({
      theme: 'wooac',
      position: wooac_vars.notiny_position,
      image: notiny_image,
      text: notiny_text,
    });
  } else {
    if (jQuery.trim(jQuery('.wooac-popup').html()).length) {
      jQuery.magnificPopup.open({
        items: {
          src: jQuery('.wooac-popup'),
          type: 'inline',
        },
        mainClass: 'mfp-wooac',
        callbacks: {
          beforeOpen: function() {
            this.st.mainClass = 'mfp-wooac ' + wooac_vars.effect;
          },
        },
      });
    }
  }
}
