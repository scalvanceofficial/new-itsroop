import PhotoSwipeLightbox from './photoswipe-lightbox.esm.min.js';
import PhotoSwipe from './photoswipe.esm.min.js';

$(window).on('load', function () {
  const mainSwiper = new Swiper('#main-swiper', {
    spaceBetween: 0,
    observer: true,
    observeParents: true,
    navigation: {
      nextEl: '.thumbs-next',
      prevEl: '.thumbs-prev',
    },
    thumbs: {
      swiper: new Swiper('#thumbs-swiper', {
        spaceBetween: 10,
        slidesPerView: 'auto',
        freeMode: true,
        direction: 'vertical',
        watchSlidesProgress: true,
        observer: true,
        observeParents: true,
        breakpoints: {
          0: { direction: 'horizontal', slidesPerView: 4 },
          768: { direction: 'vertical' },
        },
      }),
    },
  });

  // Zoom setup (use specific container)
  const zoomPane = document.querySelector('.tf-zoom-main');
  if (window.matchMedia('(min-width: 768px)').matches) {
    document.querySelectorAll('.tf-image-zoom').forEach(el => {
      new Drift(el, {
        paneContainer: zoomPane,
        zoomFactor: 2,
        inlinePane: false,
        handleTouch: false,
        hoverBoundingBox: true,
        containInline: true,
      });
    });
  }

  // Lightbox
  const lightbox = new PhotoSwipeLightbox({
    gallery: '#main-swiper',
    children: 'a',
    pswpModule: PhotoSwipe,
    bgOpacity: 1,
    secondaryZoomLevel: 2,
    maxZoomLevel: 3,
  });

  lightbox.init();

  lightbox.on('change', () => {
    const { pswp } = lightbox;
    mainSwiper.slideTo(pswp.currIndex, 0, false);
  });

  lightbox.on('afterInit', () => {
    if (mainSwiper?.autoplay?.enabled) mainSwiper.autoplay.stop();
  });

  lightbox.on('closingAnimationStart', () => {
    const { pswp } = lightbox;
    mainSwiper.slideTo(pswp.currIndex, 0, false);
    if (mainSwiper?.autoplay?.enabled) mainSwiper.autoplay.start();
  });
});
