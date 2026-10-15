export default class ConsuniSlider {
  constructor() {
    const sliderElement = document.querySelector('.consuni__slider');

    if (!sliderElement) return;

    new Swiper(sliderElement, {
      slidesPerView: 1.25,
      spaceBetween: 10,
      navigation: {
        nextEl: '.consuni__button--next',
        prevEl: '.consuni__button--prev',
      },
      breakpoints: {
        480: {
          slidesPerView: 2.25,
          spaceBetween: 10,
        },
        768: {
          slidesPerView: 4.25,
          spaceBetween: 10,
        },
        1100: {
          slidesPerView: 5.25,
          spaceBetween: 10,
        },
      },
    });
  }
}