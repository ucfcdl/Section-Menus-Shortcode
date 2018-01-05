(function ($) {
  $.fn.sectionMenu = function (options) {
    const settings = $.extend({
      nav: this,
      wrapper: this.closest('.sections-menu-wrapper'),
      selector: this.data('selector') ? this.data('selector') : '.auto-section',
      offset: this.height(),
      scrollTime: 750,
      menuCloseTime: 500
    }, options);

    // Triggered when anchor is clicked
    const onClick = (e) => {
      e.preventDefault();

      const hash = e.target.hash;
      const $target = $(hash);
      let scrollTo;

      if ($target.length) {
        // If mobile menu visible
        if ($navbarToggler.filter(':visible').length) {
          $navbarToggler.trigger('click');

          // Wait for menu to close before calculating scrollTo
          setTimeout(() => {
            const $navbar = settings.wrapper.find('.navbar');
            // Add +1 to ensure the correct nav item is highlighted when scrolled to
            if ($navbar.hasClass('fixed-top')) {
              scrollTo = $target.offset().top - $navbar.height() + 1;
            } else {
              scrollTo = $target.offset().top - this.height() + 1;
            }
            $('html, body').animate({
              scrollTop: scrollTo
            }, settings.scrollTime);
          }, settings.menuCloseTime);

        } else {
          // Add +1 to ensure the correct nav item is highlighted when scrolled to
          $('html, body').animate({
            scrollTop: $target.offset().top - this.height() + 1
          }, settings.scrollTime);
        }
      }

      if (history.pushState) {
        history.pushState(null, null, hash);
      } else {
        location.hash = hash;
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
    const $sections      = $(settings.selector);
    const $menuList      = $(settings.nav).find('ul.nav');
    const $navbarToggler = settings.wrapper.find('.navbar-toggler');
    const $ucfhb         = $('#ucfhb');
    const $ucfhbScript   = $('script[src*="//universityheader.ucf.edu/bar/js/university-header"]');
    let ucfhbHeight      = 50;

    if ($ucfhb.length && $ucfhb.height()) {
      // If we can detect the header's height, use it instead
      ucfhbHeight = $ucfhb.height();
    } else if (!$ucfhbScript.length) {
      // If the header script isn't present at all, don't use the header height
      ucfhbHeight = 0;
    }

    settings.offset = this.offset().top + ucfhbHeight - this.height();

    $sections.each(addToMenu);
    if (settings.wrapper) {
      $(settings.wrapper).css('min-height', this.height());
    }
    $(window).on('scroll', onScroll);
    $('body').scrollspy({
      target: this,
      offset: this.height()
    });

    return this;
  };

  $(document).ready(($) => {
    const $sectionsMenu = $('.sections-menu');
    if ($sectionsMenu.length) {
      $sectionsMenu.sectionMenu();
    }
  });

}(jQuery));
