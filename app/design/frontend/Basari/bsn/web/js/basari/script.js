require(['jquery','countDown','domReady'], function(jQuery) {
   jQuery(document).ready(function(){
    
	$(".address_form_container").hide();
	  $("button.address_add_btn").click(function () {
		$(this).toggleClass("active").next().slideToggle("fast");

		if ($.trim($(this).text()) === "Add") {
		  $(this).text("Cancel ");
		} else {
		  $(this).text("Add ");
		}

		return false;
	  });
	  $("a[href='" + window.location.hash + "']")
		.parent(".address_add_btn")
		.click();
	
	$(".address_edit_form_container").hide();
	  $("button.address_edit_btn").click(function () {
		$(this).toggleClass("active").next().slideToggle("fast");

		if ($.trim($(this).text()) === "Edit") {
		  $(this).text("Cancel ");
		} else {
		  $(this).text("Edit ");
		}

		return false;
	  });
	  $("a[href='" + window.location.hash + "']")
		.parent(".address_add_btn")
		.click();
	
	var tabs = $(".checkout_select_saved_address_item");
	  tabs.click(function (e) {
		e.preventDefault();
		tabs.removeClass("active");
		$(this).addClass("active");
	  });
	
	//login tab
	const loginForm = document.querySelectorAll(".login_form_wrap");
	const loginFormTab = document.querySelectorAll(".login_tab_label_item");
	const loginFormSlider = document.querySelector(".login_tab_label_wrap");

	function hideLoginFormTabContent() {
	  loginForm.forEach((item) => {
		item.style.display = "none";
	  });
	  loginFormTab.forEach((item) => {
		item.classList.remove("active");
	  });
	}

	function showLoginFormTabContent(i = 0) {
	  loginForm[i].style.display = "flex";
	  loginFormTab[i].classList.add("active");
	}

	hideLoginFormTabContent();
	showLoginFormTabContent();

	loginFormSlider.addEventListener("click", (e) => {
	  const target = e.target;
	  if (target) {
		loginFormTab.forEach((item, i) => {
		  if (target == item) {
			hideLoginFormTabContent();
			showLoginFormTabContent(i);
		  }
		});
	  }
	});	
	
	$(".product_item_wishlist_btn").on("click", function () {
		$(this).toggleClass("in_wishlist");
	  });
	
		//shop product tab
	const shopcontent = document.querySelectorAll(".shop_product_list_wrap");
	const shoptab = document.querySelectorAll(".shop_product_tab_label_item");
	const shopslider = document.querySelector(".shop_product_tab_label_wrap");

	function hideProductTabContent() {
	  shopcontent.forEach((item) => {
		item.style.display = "none";
	  });
	  shoptab.forEach((item) => {
		item.classList.remove("active");
	  });
	}

	function showProductTabContent(i = 0) {
	  shopcontent[i].style.display = "flex";
	  shoptab[i].classList.add("active");
	}

	hideProductTabContent();
	showProductTabContent();

	shopslider.addEventListener("click", (e) => {
	  const target = e.target;
	  if (target) {
		shoptab.forEach((item, i) => {
		  if (target == item) {
			hideProductTabContent();
			showProductTabContent(i);
		  }
		});
	  }
	});
		
  });
});


