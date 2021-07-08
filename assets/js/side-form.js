window.addEventListener("load", function (e) {
  astra_child_onload_side_form();
});

function astra_child_onload_side_form() {
  const wrapper = document.querySelector(".astra-child__side-form-wrapper");

  if (!wrapper) {
    return;
  }

  // Display the wrapper.
  wrapper.style.display = "flex";

  const button = wrapper.querySelector(".astra-child__side-form-button");

  wrapper.style.right = `${-Number(wrapper.offsetWidth)}px`;
  button.style.left = `${-Number(button.offsetWidth)}px`;

  button.addEventListener("click", formSlide);

  function formSlide(event) {
    event.preventDefault();

    wrapper.classList.toggle("visible");

    if (wrapper.classList.contains("visible")) {
      wrapper.style.transform = `translate3d( 0px, 0px, 0px)`;
    } else {
      wrapper.style.transform = `translate3d( ${-wrapper.offsetWidth}px, 0px, 0px)`;
    }
  }
}
