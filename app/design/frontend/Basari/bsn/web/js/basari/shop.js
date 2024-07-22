// list wishlist icon

$(".product_item_wishlist_btn").on("click", function () {
  $(this).toggleClass("in_wishlist");
});

// filter clr
$(".shop_filter_clrs_item input[type=checkbox]").on("change", function () {
  if ($(this).prop("checked")) {
    $(this).parent().addClass("taken");
  } else {
    $(this).parent().removeClass("taken");
  }
});

// price filter
var lowerSlider = document.querySelector("#lower");
var upperSlider = document.querySelector("#upper");

document.querySelector("#two").value = upperSlider.value;
document.querySelector("#one").value = lowerSlider.value;

var lowerVal = parseInt(lowerSlider.value);
var upperVal = parseInt(upperSlider.value);

upperSlider.oninput = function () {
  lowerVal = parseInt(lowerSlider.value);
  upperVal = parseInt(upperSlider.value);

  if (upperVal < lowerVal + 4) {
    lowerSlider.value = upperVal - 4;
    if (lowerVal == lowerSlider.min) {
      upperSlider.value = 4;
    }
  }
  document.querySelector("#two").value = this.value;
};

lowerSlider.oninput = function () {
  lowerVal = parseInt(lowerSlider.value);
  upperVal = parseInt(upperSlider.value);
  if (lowerVal > upperVal - 4) {
    upperSlider.value = lowerVal + 4;
    if (upperVal == upperSlider.max) {
      lowerSlider.value = parseInt(upperSlider.max) - 4;
    }
  }
  document.querySelector("#one").value = this.value;
};

//shop product tab
const shopcontent = document.querySelectorAll(".shop_product_list_wrap");
const shoptab = document.querySelectorAll(".shop_product_tab_label_item");
const shopslider = document.querySelector(".shop_product_tab_label_wrap");

function hideTabContent() {
  shopcontent.forEach((item) => {
    item.style.display = "none";
  });
  shoptab.forEach((item) => {
    item.classList.remove("active");
  });
}

function showTabContent(i = 0) {
  shopcontent[i].style.display = "flex";
  shoptab[i].classList.add("active");
}

hideTabContent();
showTabContent();

shopslider.addEventListener("click", (e) => {
  const target = e.target;
  if (target) {
    shoptab.forEach((item, i) => {
      if (target == item) {
        hideTabContent();
        showTabContent(i);
      }
    });
  }
});
