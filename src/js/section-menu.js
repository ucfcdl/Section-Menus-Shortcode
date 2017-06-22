(function ($) {
  $.fn.sectionMenu = function (options) {
    const settings = $.extend({
      selector: this.data('selector') ? this.data('selector') : '.auto-section',
      offset: this.height(),
      scrollTime: 750
    }, options);

    // Triggered when anchor is clicked
    const onClick = (e) => {
      e.preventDefault();

      const hash = e.target.hash;
      const $target = $(hash);
      const scrollTo = $target.offset().top - this.height();

      if ($target.length) {
        $('html, body').animate({
          scrollTop: scrollTo
        }, settings.scrollTime);
      }
    };

    // Callback to add menu items
    const addToMenu = ($i, $section) => {
      const $item  = $($section);
      const url = $item.attr('id');
      let text;

      if (typeof $item.data('section-link-title') !== 'undefined') {
        text = $item.data('section-link-title');
      } else {
        text = $item.find('.section-title').text();
      }

      if (!text) {
        return;
      }

      const $listItem = $('<li class="nav-item"></li>');
      const $anchor = $(`<a class="section-link nav-link" href="#${url}">${text}</a>`);

      $anchor.on('click', onClick);
      $listItem.append($anchor);
      $menuList.append($listItem);
    };

    // Called whenever window is scrolled
    const onScroll = () => {
      if ($(window).scrollTop() >= settings.offset) {
        this.addClass('fixed-top');
      } else {
        this.removeClass('fixed-top');
      }
    };

    // Initial constants
    const $sections = $(settings.selector);
    const $menuList = $('ul.nav');
    const $firstSection = $sections.first();

    settings.offset = $firstSection.offset().top - this.height();

    $sections.each(addToMenu);
    $(window).on('scroll', onScroll);
    $('body').scrollspy({
      target: this,
      offset: this.height()
    });

    return this;
  };

  $(document).ready(($) => {
    $('.sections-menu').sectionMenu();
  });

}(jQuery));
