window.addEventListener("load", function (e) {
  astra_child_onload_function();
});

function astra_child_onload_function() {
  /* Do things after DOM has fully loaded */

  var astra_meta_box = document.querySelector("#astra_settings_meta_box");
  if (astra_meta_box != null) {
    var body_class = document.querySelector("body");
    var content_width_checkbox = document.getElementById("post-content-width");

    if (content_width_checkbox != null) {
      update_class(content_width_checkbox.value);
    }

    content_width_checkbox.addEventListener("change", function () {
      update_class(content_width_checkbox.value);
    });
  }
}

function update_class(className) {
  var body_class = document.querySelector("body");

  if (body_class) {
    body_class.classList.remove(`astra-child__wide-view`);
    body_class.classList.add(`astra-child__${className}-view`);
  }
}
