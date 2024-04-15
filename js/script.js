document.addEventListener("DOMContentLoaded", function () {
  // Initialize Materialize components
  var modals = document.querySelectorAll(".modal");
  M.Modal.init(modals);

  var items = document.querySelectorAll(".collapsible");
  M.Collapsible.init(items);

  var selects = document.querySelectorAll("select");
  M.FormSelect.init(selects);

  //min date
  var today = new Date();
  var yyyy = today.getFullYear();
  var mm = String(today.getMonth() + 1).padStart(2, "0"); // January is 0!
  var dd = String(today.getDate()).padStart(2, "0");
  var minDate = yyyy + "-" + mm + "-" + dd;

  // Initialize datepicker and timepicker
  var datepickers = document.querySelectorAll(".datepicker");
  var timepickers = document.querySelectorAll(".timepicker");
  M.Datepicker.init(datepickers, {
    format: "yyyy-mm-dd",
    autoClose: true,
    minDate: new Date(minDate),
  });
  M.Timepicker.init(timepickers, {
    twelveHour: true,
    minTime: "09:30",
    maxTime: "19:00",
  });

  // mobile sideNav
  const sideNav = document.querySelector(".sidenav");
  M.Sidenav.init(sideNav, {
    edge: "right",
  });

  // image slider
  const slider = document.querySelector(".slider");
  M.Slider.init(slider, {
    height: 650,
    transition: 500,
    interval: 4000,
  });

  // about cards
  const toggleIcons = document.querySelectorAll(".toggle-icon");
  toggleIcons.forEach((icon) => {
    icon.addEventListener("click", function () {
      const panel = this.parentElement;
      const hiddenText = panel.querySelector(".hidden-text");

      // Toggle the visibility of hidden text
      hiddenText.classList.toggle("show");
    });
  });
});
