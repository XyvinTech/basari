require(['jquery','countDown','domReady'], function($) {
   $(document).ready(function(){
	  // hero banner

  var owl = $(".carousel2");
  owl.owlCarousel({
    items: 1,
    nav: false,
    dots: false,
    loop: true,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
  });

  // shop by shape

  var owl = $(".carousel1");
  owl.owlCarousel({
    margin: 50,
    nav: false,
    dots: false,
    responsive: true,
    responsive: {
      0: {
        items: 3,
      },
      600: {
        items: 4,
      },
      1024: {
        items: 4,
      },
      1440: {
        items: 6,
      },
    },
  });

  // on scroll header top hide

  var c,
    currentScrollTop = 0,
    navbar = $("#header_top");

  $(window).scroll(function () {
    var a = $(window).scrollTop();
    var b = navbar.height();

    currentScrollTop = a;

    if (c < currentScrollTop && a > b + b) {
      navbar.addClass("scrollUp");
    } else if (c > currentScrollTop && !(a <= b)) {
      navbar.removeClass("scrollUp");
    }
    c = currentScrollTop;

    console.log(a);
  });

  // product wishlist

  $(".product_item_wishlist_btn").on("click", function () {
    $(this).toggleClass("in_wishlist");
  });		
  });
});