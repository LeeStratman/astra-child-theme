document.addEventListener("DOMContentLoaded", () => {
  "use strict";

  /**
   * Get DOM elements.
   */
  const body = document.querySelector("body");
  const menu = document.querySelector(".main-navigation .main-header-menu");

  if (!body || !menu) {
    return;
  }

  /**
   * Get header logo.
   */
  const logo = document.querySelector(".custom-logo");
  const footerLogo = document.querySelector("footer img");
  const logoSource = logo ? logo.src : "";

  /**
   * Get default theme.
   */
  const defaultTheme = getCurrentTheme();

  /**
   * Look for alternate logo.
   */
  const logoAlt = document.querySelector(".astra-child__logo-alt");
  const logoAltSource = logoAlt ? logoAlt.dataset.altsrc : "";

  const allImages = document.querySelectorAll(".wp-block-image");

  let images = [];

  allImages.forEach((element) => {
    images.push(new Image(element));
  });

  function Image(element) {
    this.element = element;
    let altsrc = element.dataset.altsrc;
    if (altsrc) {
      this.altsrc = altsrc;
    }
    let imageTag = element.querySelector("img");
    if (imageTag) {
      this.img = imageTag;
      this.src = imageTag.src ? imageTag.src : "";
    }
  }

  /**
   * Create Menu.
   */
  const themeBtn = createThemeMenu();

  /**
   * Set initial value.
   */
  themeInit();

  /**
   * Inialize theme.
   */
  function themeInit() {
    setTheme(getThemeFromStorage());
  }

  /**
   * Creates a dark mode menu item and inserts
   * it into the primary header.
   */
  function createThemeMenu() {
    let menuItem = document.createElement("li");
    menuItem.classList.add("dark-mode-menu-item");

    let darkmodeBtn = document.createElement("button");
    darkmodeBtn.classList.add("dark-mode-button");

    menuItem.appendChild(darkmodeBtn);
    menu.appendChild(menuItem);

    return darkmodeBtn;
  }

  /**
   * Get current theme based on the body class.
   */
  function getCurrentTheme() {
    if (body.classList.contains("darkmode")) {
      return "dark";
    }

    return "light";
  }

  /**
   * Toggle the theme.
   */
  function toggleTheme() {
    let current = getCurrentTheme();
    if ("light" === current) {
      setTheme("dark");
    } else {
      setTheme("light");
    }
  }

  /**
   * Set the theme.
   *
   * @param {string} theme The theme to change to.
   */
  function setTheme(theme = "") {
    if ("dark" === theme) {
      body.classList.add("darkmode");
      themeBtn.innerHTML = "☀️";
      logo.src = logoAltSource;
      if (footerLogo) {
        footerLogo.src = logoAltSource;
        footerLogo.srcset = "";
      }
      updateImages("dark");
      saveTheme("dark");
    } else if ("light" === theme) {
      body.classList.remove("darkmode");
      themeBtn.innerHTML = "🌙";
      logo.src = logoSource;
      if (footerLogo) {
        footerLogo.src = logoSource;
        footerLogo.srcset = "";
      }
      updateImages("light");
      saveTheme("light");
    }
  }

  /**
   * Update image array.
   *
   * @param {string} mode Dark or light.
   */
  function updateImages(mode) {
    images.forEach((image) => {
      if (
        image.src === image.img.src &&
        "" !== image.altsrc &&
        "dark" === mode
      ) {
        image.img.src = image.altsrc;
      } else if ("light" === mode && image.altsrc === image.img.src) {
        image.img.src = image.src;
      }
    });
  }

  /**
   * Save value to local storage.
   *
   * @param {Mixed} value Value to save.
   */
  function saveTheme(value) {
    window.localStorage.setItem("astraChildTheme", JSON.stringify(value));
    setCookie("astraChildTheme", value, { secure: true, "max-age": 3600 });
  }

  /**
   * Get value from localstorage.
   */
  function getThemeFromStorage() {
    const theme = window.localStorage.getItem("astraChildTheme");

    return theme ? JSON.parse(theme) : defaultTheme;
  }

  function getCookie(name) {
    let matches = document.cookie.match(
      new RegExp(
        "(?:^|; )" +
          name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
          "=([^;]*)"
      )
    );
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }

  function setCookie(name, value, options = {}) {
    options = {
      path: "/",
      // add other defaults here if necessary
      ...options,
    };

    if (options.expires instanceof Date) {
      options.expires = options.expires.toUTCString();
    }

    let updatedCookie =
      encodeURIComponent(name) + "=" + encodeURIComponent(value);

    for (let optionKey in options) {
      updatedCookie += "; " + optionKey;
      let optionValue = options[optionKey];
      if (optionValue !== true) {
        updatedCookie += "=" + optionValue;
      }
    }

    document.cookie = updatedCookie;
  }

  function deleteCookie(name) {
    setCookie(name, "", {
      "max-age": -1,
    });
  }

  themeBtn.addEventListener("click", (event) => {
    toggleTheme();
  });
});
