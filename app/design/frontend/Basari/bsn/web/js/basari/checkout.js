require(['jquery','domReady'], function($) {
   $(document).ready(function(){
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
	});

	// checkout
	//addresses select
	(function ($) {
	  var tabs = $(".checkout_select_saved_address_item");
	  tabs.click(function (e) {
		e.preventDefault();
		tabs.removeClass("active");
		$(this).addClass("active");
	  });
   
  });
});
