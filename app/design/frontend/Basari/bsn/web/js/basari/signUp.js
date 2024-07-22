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
