/*global $, jQuery, l_data */
jQuery(document).ready(function ($) {
  'use strict';

  function menu() {
    $(function () {
      $('.menu-item').on('hover', function (e) {
        if ($('ul', this).length) {
          var elm = $('.sub-menu', this);
          var off = elm.offset();
          var l = off.left;
          var w = elm.width();
          var docH = $(window).height();
          var docW = $(window).width();

          var isEntirelyVisible = (l + w <= docW);

          if (!isEntirelyVisible) {
            $(this).addClass('edge');
          } else {
            $(this).removeClass('edge');
          }
        }
      });
    });
    // menu settings.
    const menuTrigger = document.querySelector('.menu--trigger');
    const menuTriggerClose = document.querySelector('.menu--close');
    const menu = document.querySelector('.menu--lisfinity');
    if (menu && menuTrigger) {
      menuTrigger.addEventListener('click', e => {
        if (menu.classList.contains('hidden')) {
          menu.classList.remove('hidden');
        } else {
          menu.classList.add('hidden');
        }
      });
      menuTriggerClose.addEventListener('click', e => {
        menu.classList.add('hidden');
      });
    }

    // sub menu settings.
    if (window.innerWidth <= 1024) {
      $(document).on('click', '.has-sub', function (e) {
        e.preventDefault();
        if ($(this).next('.sub-menu').css('display') === 'flex') {
          $(this).next('.sub-menu').css('display', 'none');
          $(this).find('svg').css('transform', 'none');
        } else {
          $(this).next('.sub-menu').css('display', 'flex');
          $(this).find('svg').css('transform', 'rotate(180deg)');
        }
      });
    }

    const leaveCommentTrigger = document.querySelectorAll('.action--leave-comment');
    leaveCommentTrigger.forEach(trigger => {
      trigger.addEventListener('click', e => {
        const commentForm = document.getElementById('commentform');
        if (commentForm) {
          commentForm.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  }

  menu();
});

