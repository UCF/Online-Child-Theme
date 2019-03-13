(($) => {
  /**
  * jQuery function for assigning and performing an action when
  * a landing page's call-to-action (CTA) button is clicked.
  * @return {void}
  **/
  $.fn.landingCTA = function () {
    /* eslint consistent-return: "off" */

    const $dialog = $('#landing-dialog');
    const action  = $dialog.data('dialog-action') || 'scrolltop';

    /**
    * Performs the necessary action to toggle the landing
    * page dialog (modal, inline form, etc.)
    * @param {object} e The event passed in from an event trigger
    * @return {void}
    **/
    const doAction = function (e) {
      e.preventDefault();

      switch (action) {
        case 'modal':
          $dialog.modal('show');
          break;
        case 'scrolltop':
        default:
          $([document.documentElement, document.body]).animate({
            scrollTop: $dialog.offset().top
          }, 500);
          break;
      }
    };

    /**
    * The primary each-loop for the jQuery objects.
    * Don't assign an event handler if $dialog isn't available.
    **/
    if ($dialog.length) {
      return this.each(() => {
        /**
        * The on click event for the button
        **/
        this.on('click', doAction);
      });
    }
  };

  /**
  * Automatically initialize landingCTA() for elements
  * with the `.landing-cta` class on document ready
  **/
  $(document).ready(() => {
    $('.landing-cta').landingCTA();
  });
})(jQuery);
