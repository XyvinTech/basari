require(['jquery'], function ($) {
	$(document).ready(function () {
		/*$(".product_variant_label_box_arrow").click(function () {
			$(this).parent().next().toggle();
		})*/
		
		$('.my_account_pg').find('.column.main').addClass('img_skeleton skeleton');
		// shop page filter toggle
		$('.filter_label_box_child').click(function (e) {
			e.preventDefault();
			$(this).parent().next().toggleClass('show');
		})

		$(".menu-toggle").click(function () {
			$(".header_menu_items_wrap").toggleClass("mobile-nav");
			$(this).toggleClass("is-active");
		});
		$("#menu-btn").click(function () {
			// If the clicked element has the active class, remove the active class from EVERY .nav-link>.state element
			if ($(this).hasClass("is-active")) {
				$(".navbar-toggler").removeClass("is-active");
			}
			// Else, the element doesn't have the active class, so we remove it from every element before applying it to the element that was clicked
			else {
				$(".navbar-toggler").removeClass("is-active");
				$(this).addClass("is-active");
			}
		});
		$(".navbar-toggler").click(function () {
			$(".navbar-collapse").slideToggle(300);
		});

		$('.menu-link').click(function (e) {
			if ($(window).innerWidth() <= 992) {
				e.preventDefault();
				$(this).next().slideToggle();
			}
			//   $(this).parent().children('sub-menu').addClass('display');
		});

		$('.form.minisearch').click(function (e) {
			e.preventDefault();
			$(this).children().find('.label').hide();
			$(this).children().find('.control').show();
			$(this).children('.actions').show();
		});

		$('.footer_list_heading').click(function (e) {
			if ($(window).innerWidth() <= 768) {
				e.preventDefault();
				$(this).next().slideToggle();
				$(this).children().toggleClass('marginBottom');
				console.log($(this).children('span').children());
				$(this).children('span').children().toggleClass('fa-chevron-down');
				$(this).children('span').children().toggleClass('fa-chevron-up');
			}
		});

		$(".header_menu_icon_user_btn").click(function () {
			// $('.header_menu_icon_user').toggleClass('show');
			$('.header_user_icon_hover_list').toggleClass('show');
		});

		$(".faq_header_parent").click(function () {
			// $('.header_menu_icon_user').toggleClass('show');
			$(this).next().toggleClass('show');
		});

		/*$(".product_variant_label_box_arrow").click(function () {
			console.log('clicked');
			$(this).parent().next().toggle();
		});*/

		/*jQuery(".product_variant_label_box_arrow").click(function () {

			if(jQuery(this).parents('.product_variant_label_box'))
			{
				jQuery(this).parents('.product_variant_label_box').next().toggle();
			}
			else
			{
				jQuery(this).parents('.product_variant_label_right').next('div').toggle();
			}

		});*/

		//skeleton animation
		const skeletons = document.querySelectorAll(".product_card");
		console.log('calling skeletons');
		skeletons.forEach((skeletons) => {
			setTimeout(() => {
				skeletons.classList.remove("skeleton");

				skeletons.querySelectorAll(".hide-img").forEach((el) => el.classList.remove("hide-img"));
				skeletons.querySelectorAll(".hide-text").forEach((el) => el.classList.remove("hide-text"));
			}, 3000);
		});
		const filter = document.querySelectorAll(".shop_filter_wrap");

		filter.forEach((filter) => {
			setTimeout(() => {
				filter.classList.remove("skeleton");

				filter.querySelectorAll(".hide-img").forEach((el) => el.classList.remove("hide-img"));
				filter.querySelectorAll(".hide-text").forEach((el) => el.classList.remove("hide-text"));
			}, 3000);
		});
		const productTab = document.querySelectorAll(".product_tab_wrapper");

		productTab.forEach((filter) => {
			setTimeout(() => {
				filter.classList.remove("skeleton");

				productTab.querySelectorAll(".hide-img").forEach((el) => el.classList.remove("hide-img"));
				productTab.querySelectorAll(".hide-box").forEach((el) => el.classList.remove("hide-box"));
				productTab.querySelectorAll(".hide-text").forEach((el) => el.classList.remove("hide-text"));
			}, 3000);
		});
		const image = document.querySelectorAll(".img_skeleton");

		image.forEach((filter) => {
			setTimeout(() => {
				filter.classList.remove("skeleton");

				try {
					image.querySelectorAll(".hide-img").forEach((el) => el.classList.remove("hide-img"));
					image.querySelectorAll(".hide-box").forEach((el) => el.classList.remove("hide-box"));
					image.querySelectorAll(".l-box").forEach((el) => el.classList.remove("l-box"));
					image.querySelectorAll(".hide-text").forEach((el) => el.classList.remove("hide-text"));
				} catch (err) {

				}

			}, 3000);
		});

	});
});