export default class HeroSlider {
  constructor() {
    const heroSliderElement = document.querySelector(".hero-slider");

    if (!heroSliderElement) return;

    new Swiper(".hero-slider", {
      loop: true,
      speed: 800,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".hero-slider .swiper-button-next",
        prevEl: ".hero-slider .swiper-button-prev",
      },
      pagination: {
        el: ".hero-slider .swiper-pagination",
        clickable: true,
        renderBullet(index, className) {
          return `<span class="${className}">${index + 1}</span>`;
        },
      },
    });
  }
}
