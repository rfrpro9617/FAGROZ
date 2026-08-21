export default class PreviewSlider {
  constructor() {
    const previewSliderElement = document.querySelector('.preview-slider');

    if (!previewSliderElement) return;

    new Swiper('.preview-slider', {
      slidesPerView: 3,
      spaceBetween: 30,
      breakpoints: {
        0: {
          slidesPerView: 1,
          spaceBetween: 30,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      },
      pagination: {
        el: '.preview-slider .swiper-pagination',
        clickable: true,
      },
    });
  }
}
