(function ($) {
  function debounce(func, wait, immediate) {
    let timeout;

    return function () {
      const context = this,
        args = arguments;

      const later = function () {
        timeout = null;
        if (!immediate) {
          func.apply(context, args);
        }
      };

      const callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);

      if (callNow) {
        func.apply(context, args);
      }
    };
  }

  const onClick = (e) => {
    e.preventDefault();

    let $target = $(this.hash);
    $target = $target.length ? $target : $(`[name=${this.hash.slice()}]`);

    const scrollTo = $target.offset().top - 50;
    if ($(window).width() < 991) {
      $sectionMenu.collapse('toggle');
    }

    if ($target.length) {
      $('html, body').animate({
        scrollTop: scrollTo
      }, 750);
    }
  };

  const addToMenu = ($i, $section) => {
    let $item  = $($section),
      url = $item.attr('id'),
      text;

    if (typeof $item.data('section-link-title') !== 'undefined') {
      text = $item.data('section-link-title');
    } else {
      text = $item.find('.section-title').text();
    }
    let $listItem = $('<li></li>'),
      $anchor = $(`<a class="section-link" href="#${url}">${text}</a>`);
    $anchor.on('click', onClick);
    $listItem.append($anchor);
    $menuList.append($listItem);
  };

  const setBumperHeight = () => {
    $bumper.height($menu.height());
  };

  const onScroll = () => {
    if ($(window).scrollTop() >= offset) {
      $menu.removeClass('center');
      $menu.addClass('navbar-fixed-top');
      $('body').addClass('fixed-navbar');
    } else {
      $menu.addClass('center');
      $menu.removeClass('navbar-fixed-top');
      $('body').removeClass('fixed-navbar');
    }
  };

  const onResize = debounce(() => {
    offset = $firstSection.offset().top - $menu.height(); // Reduce by 50px to account for university header.
    setBumperHeight();
  }, 100);

  const $sectionMenu  = $('#sections-menu'),
    selector      = $sectionMenu.data('selector'),
    $sections     = $(selector),
    $menuList     = $sectionMenu.find('ul.nav'),
    $menu         = $('#sections-navbar'),
    $firstSection = $sections.first(),
    $bumper       = $menu.next('.navbar-bumper');

  let offset  = $firstSection.offset().top;

  if (!$sectionMenu.length) {
    return;
  }

  $.each($sections, addToMenu);
  $(document).on('scroll', onScroll);
  $('body').scrollspy({
    target: '#sections-menu',
    offset: 60
  });
  $(window).on('resize', onResize);
  setBumperHeight();
  onScroll();

}(jQuery));
