export default class PreviewSlider {
  constructor() {
    const previewSliderElement = document.querySelector('.preview-slider');

    if (!previewSliderElement) return;

    const isGraduationSlider = previewSliderElement.closest('.page-graduation');
    const slidesPerView = isGraduationSlider ? 'auto' : 3;
    const breakpoints = isGraduationSlider
      ? {
        0: {
          slidesPerView: 'auto',
          spaceBetween: 30,
        },
      }
      : {
        0: {
          slidesPerView: 1,
          spaceBetween: 30,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      };

    new Swiper('.preview-slider', {
      slidesPerView,
      spaceBetween: 30,
      breakpoints,
      pagination: {
        el: '.preview-slider .swiper-pagination',
        clickable: true,
      },
    });
  }
}
