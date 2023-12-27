

$(document).ready(function () {
   // work-with-section
   $(".workwith-slider").slick({
      slidesToShow: 3,
      slidesToScroll: 1.3,
      arrows: true,
      dots: false,
      centerMode: false,
      margin: 20,
      speed: 300,
      infinite: false,
      autoplaySpeed: 5000,
      autoplay: false,
      draggable: false,
      responsive: [{
         breakpoint: 1024,
         settings: {
            slidesToShow: 3
         }
      },
      {
         breakpoint: 991,
         settings: {
            slidesToShow: 3
         }
      },
      {
         breakpoint: 768,
         settings: {
            slidesToShow: 1
         }
      }]
   });

   // case-study-section
   $(".casestudy-slider").slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      centerMode: false,
      margin: 20,
      speed: 300,
      infinite: false,
      autoplaySpeed: 5000,
      autoplay: false,
      draggable: false,
      responsive: [{
         breakpoint: 1025,
         settings: {
            slidesToShow: 2
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

   // latest-report-section
   $(".latestreport-slider").slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      centerMode: false,
      margin: 20,
      speed: 300,
      infinite: false,
      autoplaySpeed: 5000,
      autoplay: false,
      draggable: false,
      responsive: [{
         breakpoint: 1025,
         settings: {
            slidesToShow: 2
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
      }]
   });

   // insights-section
   $(".insights-slider").slick({
      slidesToShow: 2,
      slidesToScroll: 1.1,
      arrows: true,
      dots: false,
      centerMode: false,
      margin: 20,
      speed: 300,
      infinite: false,
      draggable: false,
      autoplaySpeed: 5000,
      autoplay: false,
      responsive: [{
         breakpoint: 1024,
         settings: {
            slidesToShow: 2
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

   // Achievements-section
   $(".Achievements-slider").slick({
      slidesToShow: 4,
      slidesToScroll: 2.5,
      arrows: true,
      dots: false,
      centerMode: false,
      margin: 20,
      speed: 300,
      infinite: false,
      autoplaySpeed: 5000,
      draggable: false,
      autoplay: false,
      responsive: [{
         breakpoint: 1025,
         settings: {
            slidesToShow: 2
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