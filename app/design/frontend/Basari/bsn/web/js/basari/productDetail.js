require(['jquery','countDown','domReady'], function(jQuery) {
   jQuery(document).ready(function(){
	  var tabs = $(".color");
	  tabs.click(function (e) {
		e.preventDefault();
		// var content = this.hash.replace('/', '');
		tabs.removeClass("active");
		$(this).addClass("active");
		$("#content").children("div").hide();
		$(content).fadeIn(200);
	  });
	  
	  var tabs = $(".lenses");
  tabs.click(function (e) {
    e.preventDefault();
    tabs.removeClass("active");
    $(this).addClass("active");
  });
   
	$(".product_wishlist_btn").click(function () {
	  $(this).text(function (i, v) {
		return v === "wishlist item" ? "wishlisted" : "wishlist item";
	  });
	});
	
	// list wishlist icon relative product
	$(".product_item_wishlist_btn").on("click", function () {
	  $(this).toggleClass("in_wishlist");
	});
	
	//additional tab info
	const infoTabWrapper = document.querySelector(".product_info_tab_wrapper");
	const infoTab = [...infoTabWrapper.querySelectorAll(" .product_info_tab")];
	const infoContent = [
	  ...infoTabWrapper.querySelectorAll(".product_info_tab_content "),
	];

	infoTab.forEach((tab) =>
	  tab.addEventListener("click", (e) => {
		for (product_info_tab_content of infoContent)
		  product_info_tab_content.classList.remove("active");
		for (product_info_tab of infoTab)
		  product_info_tab.classList.remove("active");
		const index = infoTab.indexOf(e.target);
		if (index != -1) {
		  e.target.classList.add("active");
		  infoContent[index].classList.add("active");
		}
	  })
	);

	// header user icon pop up
	function myFunction() {
	  var x = document.getElementById("myDIV");
	  if (x.style.display === "none") {
		x.style.display = "block";
	  } else {
		x.style.display = "none";
	  }
	}

	// cart popover
	function cartPopover() {
	  var x = document.getElementById("header_menu_cart_popover");
	  if (x.style.display === "none") {
		x.style.display = "block";
	  } else {
		x.style.display = "none";
	  }
	}

	const minus = $(".quantity__minus");
	  const plus = $(".quantity__plus");
	  const input = $(".quantity__input");
	  minus.click(function (e) {
		e.preventDefault();
		var value = input.val();
		if (value > 1) {
		  value--;
		}
		input.val(value);
	  });

	  plus.click(function (e) {
		e.preventDefault();
		var value = input.val();
		value++;
		input.val(value);
	  });
	
	 $(".product_gallery_thumbnail").click(function (e) {
		e.preventDefault();
		$(this).siblings(".product_gallery_thumbnail.active").removeClass("active");
		$(this).addClass("active");
		var index = $(this).index();
		$(".product_gallery_main_img").removeClass("active");
		$(".product_gallery_main_img").eq(index).addClass("active");
	  });
	  
	  var owl = $(".carousel3");
	  owl.owlCarousel({
		items: 1,
		nav: false,
		dots: true,
	  });
	  
	  $(".floating_wishlist_btn").on("click", function () {
	  $(this).toggleClass("in_wishlist");
	});
	
	// product detail star rating

	function rate(value) {
	  clearRates(); // clase active
	  addRates(value); // clase active
	}

	function clearRates() {
	  for (var i = 1; i <= 5; i++) {
		document.getElementById("star" + i).classList.remove("active");
	  }
	}

	function addRates(value) {
	  for (var i = 1; i <= value; i++) {
		document.getElementById("star" + i).classList.add("active");
	  }
	}

  });
});
