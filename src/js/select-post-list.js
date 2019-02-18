(($) => {
  /**
   * jQuery function for automatically updating
   * a form action based on a nested select value.
   **/
  $.fn.postListActionSelect = function () {
    /**
   * Updates the submit button of the function.
   * Called on 'change' of the select element.
   * @param {string} val The value of the select element
   * @param {object} $submit The jQuery object for the submit button
   **/
    const updateSubmit = function (val, $submit) {
      if (!val) {
        $submit.attr('disabled', 'disabled');
      } else if ($submit.is(':disabled')) {
        $submit.removeAttr('disabled');
      }
    };

    /**
   * The primary each loop for the jQuery objects
   **/
    return this.each(() => {
      const $parent = this.parents('[data-post-list-form]');
      const $submit = $parent.find('button[type="submit"]');
      let val       = this.val();

      updateSubmit(val, $submit);

      /**
     * The on change event for the select
     * element within the form
     **/
      this.on('change', () => {
        val = this.val();
        updateSubmit(val, $submit);
        $parent.attr('action', val);
      });
    });
  };

  /**
   * Automatically initialize postListActionSelect
   * for elements with the `data-post-list-select`
   * data attribute on document ready
   **/
  $(document).ready(() => {
    $('[data-post-list-select]').postListActionSelect();
  });
})(jQuery);
