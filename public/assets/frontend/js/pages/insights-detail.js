// insights-section-2
$(document).ready(function () {
   $(".insights-slider-detail").slick({
      slidesToShow: 3,
      arrows: true,
      dots: false,
      centerMode: false,
      margin: 0,
      speed: 300,
      infinite: true,
      draggable: true,
      autoplaySpeed: 3000,
      autoplay: true,
      pauseOnHover: false,
      pauseOnFocus: false,
      pauseOnDotsHover: false,
      responsive: [{
         breakpoint: 1024,
         settings: {
            slidesToShow: 3
         }
      },
      {
         breakpoint: 991,
         settings: {
            slidesToShow: 2
         }
      },
      {
         breakpoint: 768,
         settings: {
            slidesToShow: 1
         }
      }
      ]
   });
});