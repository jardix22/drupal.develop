(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.slider = {
    attach: function (context, settings) {
      $('.playlist__videos').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1
      })
    }
  };

})(jQuery, Drupal, drupalSettings);
