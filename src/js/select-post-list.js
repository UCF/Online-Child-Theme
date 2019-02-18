(($) => {
  $.fn.postListActionSelect((options) => {
    return this.each((options) => {
      const $parent = this.parent('form[data-post-list-form]');
      const $submit = $parent.find('button[type="submit"]');
      const val = this.val();

      if (!val) {
        $submit.attr('disabled', 'disabled');
      } else if ($submit.hasAttr('disabled')) {
        $submit.removeAttr('disabled');
      }

      return this.on('change', () => {
        const val = this.val();

        if (!val) {
          $submit.attr('disabled', 'disabled');
        } else if ($submit.hasAttr('disabled')) {
          $submit.removeAttr('disabled');
        }

        $parent.action = this.val();
      });
    });
  });

  $(document).ready(() => {
    $('[data-post-list-select]').postListActionSelect();
  });
})(jQuery);
