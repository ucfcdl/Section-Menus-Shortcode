(function ($) {
  $.fn.sectionMenu = function (options) {
    const $menu = this.find('#sections-menu');
    const selector = $menu.data('selector') ? $menu.data('selector') : '.auto-section';
    let autoSelect = true;
    if (typeof $menu.data('autoselect') !== 'undefined') {
      autoSelect = $menu.data('autoselect');
    } else if (selector) {
      // Backwards compatibility: assume menu items should be generated
      // automatically if the data-autoselect attr is missing, but the
      // data-selector attr is set and not empty
      autoSelect = true;
    } else {
      autoSelect = false;
    }

    const settings = $.extend({
      nav: this,
      wrapper: this.closest('.sections-menu-wrapper'),
      autoSelect: autoSelect,
      selector: selector,
      offset: this.height(),
      scrollTime: 750,
      menuCloseTime: 500
    }, options);

    let calculateOffsetTimer = null;

    // Triggered when nav link is clicked
    const onClick = (e) => {
      const currentPage = window.location.href.replace(window.location.hash, '');
      const hash = e.target.hash;
      const href = e.target.href.replace(hash, '');
      const $target = currentPage === href ? $(hash) : null;

      // Autoscroll to section on page and update document url
      // if this is a valid page anchor.
      // External links will behave normally
      if ($target.length) {
        e.preventDefault();

        // If mobile menu visible
        if ($navbarToggler.filter(':visible').length) {
          $navbarToggler.trigger('click');

          // Wait for menu to close before calculating scrollTo and scrolling
          setTimeout(() => {
            // Add +1 to ensure the correct nav item is highlighted when scrolled to
            $('html, body').animate({
              scrollTop: $target.offset().top - this.height() + 1
            }, settings.scrollTime);
          }, settings.menuCloseTime);

        } else {
          // Add +1 to ensure the correct nav item is highlighted when scrolled to
          $('html, body').animate({
            scrollTop: $target.offset().top - this.height() + 1
          }, settings.scrollTime);
        }

        if (history.pushState) {
          history.pushState(null, null, hash);
        } else {
          location.hash = hash;
        }
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

      $listItem.append($anchor);
      $menuList.append($listItem);
    };

    const calculateOffset = () => {
      const $ucfhb = $('#ucfhb');
      if ($ucfhb.height()) {
        settings.offset = this.offset().top;
        $(window).off('load scroll', null, calculateOffset);
      }
    };

    const calculateOffsetDebounce = () => {
      clearTimeout(calculateOffsetTimer);
      calculateOffsetTimer = setTimeout(calculateOffset, 250);
    };

    // Called whenever window is scrolled
    const onScroll = () => {
      if ($(window).scrollTop() >= settings.offset) {
        this.addClass('fixed-top');
      } else {
        this.removeClass('fixed-top');
      }
    };

    // Callback to apply events to menu list items
    const addLinkEvents = ($menuList) => {
      const $anchors = $menuList.find('.section-link');
      if ($anchors.length) {
        $anchors.each((i, anchor) => {
          $(anchor).on('click', onClick);
        });
      }
    };

    // Initial constants
    const $sections      = $(settings.selector);
    const $menuList      = $(settings.nav).find('ul.nav');
    const $navbarToggler = settings.wrapper.find('.navbar-toggler');
    const $ucfhbScript   = $('script[src*="//universityheader.ucf.edu/bar/js/university-header"]');

    if (settings.autoSelect && !$sections.length) {
      // If auto-selection is enabled and there are no menu items
      // to auto-generate, remove the menu and return early
      settings.wrapper.remove();
      return this;
    }

    if (settings.autoSelect) {
      $sections.each(addToMenu);
    }

    addLinkEvents($menuList);

    if (settings.wrapper) {
      $(settings.wrapper).css('min-height', this.height());
    }

    // Assign scroll events
    if ($ucfhbScript.length) {
      $(window).on('load scroll', calculateOffset);
    } else {
      settings.offset = this.offset().top;
    }
    $(window).on('resize', calculateOffsetDebounce);
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
