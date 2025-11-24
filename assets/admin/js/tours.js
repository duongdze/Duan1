document.addEventListener("DOMContentLoaded", function () {
  // ===== TOURS INDEX PAGE FUNCTIONALITY =====

  // Filter form handling with AJAX
  const filterForm = document.getElementById("tour-filters");
  if (filterForm) {
    // Auto-submit on filter change (debounced)
    let filterTimeout;
    const filterInputs = filterForm.querySelectorAll("input, select");

    filterInputs.forEach((input) => {
      input.addEventListener("input", function () {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
          submitFilters();
        }, 500);
      });

      input.addEventListener("change", function () {
        if (input.type !== "text") {
          submitFilters();
        }
      });
    });

    // Manual submit button
    const submitBtn = filterForm.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.addEventListener("click", function (e) {
        e.preventDefault();
        submitFilters();
      });
    }

    function submitFilters() {
      const formData = new FormData(filterForm);
      const params = new URLSearchParams();

      // Add action parameter
      params.append("action", "tours");

      // Add all form data
      for (let [key, value] of formData.entries()) {
        if (value.trim() !== "") {
          params.append(key, value);
        }
      }

      // Update URL without page reload
      const newUrl = window.location.pathname + "?" + params.toString();
      window.history.pushState({}, "", newUrl);

      // Show loading state
      const container = document.getElementById("tour-list-container");
      if (container) {
        container.style.opacity = "0.6";
        container.style.pointerEvents = "none";
      }

      // Reload page with new filters
      window.location.reload();
    }
  }

  // ===== Thumbnail click and Lightbox =====
  function createLightbox() {
    // remove existing lightbox if present so we recreate clean handlers
    const existing = document.getElementById("image-lightbox");
    if (existing) existing.remove();

    const lb = document.createElement("div");
    lb.id = "image-lightbox";
    lb.className = "image-lightbox";
    lb.innerHTML =
      '\n      <div class="lightbox-inner">\n        <button class="lightbox-close" aria-label="Close">&times;</button>\n        <button class="lightbox-prev" aria-label="Previous">&#8249;</button>\n        <div class="lightbox-media">\n          <img class="lightbox-image" src="" alt="">\n        </div>\n        <div class="lightbox-thumbs" aria-hidden="false"></div>\n        <button class="lightbox-next" aria-label="Next">&#8250;</button>\n      </div>';
    document.body.appendChild(lb);

    const img = lb.querySelector(".lightbox-image");
    const btnClose = lb.querySelector(".lightbox-close");
    const btnPrev = lb.querySelector(".lightbox-prev");
    const btnNext = lb.querySelector(".lightbox-next");
    const thumbsContainer = lb.querySelector(".lightbox-thumbs");

    let currentGallery = null;
    let currentIndex = 0;

    function renderThumbs(gallery, activeIdx) {
      thumbsContainer.innerHTML = "";
      if (!gallery || !gallery.length) return;
      gallery.forEach(function (src, i) {
        const div = document.createElement("div");
        div.className = "thumb" + (i === activeIdx ? " active" : "");
        const timg = document.createElement("img");
        timg.src = src;
        timg.alt = "thumb-" + i;
        timg.dataset.index = i;
        timg.addEventListener("click", function (e) {
          e.stopPropagation();
          currentIndex = i;
          img.src = src;
          // update active
          thumbsContainer.querySelectorAll(".thumb").forEach(function (t) {
            t.classList.remove("active");
          });
          div.classList.add("active");
        });
        div.appendChild(timg);
        thumbsContainer.appendChild(div);
      });
    }

    function close() {
      lb.classList.remove("open");
      img.removeAttribute("src");
      currentGallery = null;
      currentIndex = 0;
      thumbsContainer.innerHTML = "";
    }

    btnClose.addEventListener("click", close);
    lb.addEventListener("click", function (e) {
      if (e.target === lb) close();
    });

    btnPrev.addEventListener("click", function (e) {
      e.stopPropagation();
      if (!currentGallery || !currentGallery.length) return;
      currentIndex =
        (currentIndex - 1 + currentGallery.length) % currentGallery.length;
      img.src = currentGallery[currentIndex];
      // update active thumb
      thumbsContainer.querySelectorAll(".thumb").forEach(function (t) {
        t.classList.remove("active");
      });
      const active = thumbsContainer.querySelector(
        ".thumb:nth-child(" + (currentIndex + 1) + ")"
      );
      if (active) active.classList.add("active");
    });

    btnNext.addEventListener("click", function (e) {
      e.stopPropagation();
      if (!currentGallery || !currentGallery.length) return;
      currentIndex = (currentIndex + 1) % currentGallery.length;
      img.src = currentGallery[currentIndex];
      thumbsContainer.querySelectorAll(".thumb").forEach(function (t) {
        t.classList.remove("active");
      });
      const active = thumbsContainer.querySelector(
        ".thumb:nth-child(" + (currentIndex + 1) + ")"
      );
      if (active) active.classList.add("active");
    });

    document.addEventListener("keydown", function (e) {
      if (!lb.classList.contains("open")) return;
      if (e.key === "Escape") close();
      if (e.key === "ArrowLeft") btnPrev.click();
      if (e.key === "ArrowRight") btnNext.click();
    });

    return {
      open: function (gallery, index) {
        if (!gallery || !gallery.length) return;
        currentGallery = gallery;
        currentIndex = typeof index === "number" ? index : 0;
        img.src = currentGallery[currentIndex];
        renderThumbs(currentGallery, currentIndex);
        lb.classList.add("open");
      },
      close: close,
    };
  }

  window.tourLightbox = createLightbox();
  const lightbox = window.tourLightbox;

  document.querySelectorAll(".tour-card").forEach(function (card) {
    const raw = card.dataset.gallery;
    let gallery = [];
    try {
      gallery = raw ? JSON.parse(raw) : [];
    } catch (err) {
      gallery = raw ? raw.split(",") : [];
    }

    const mainImg = card.querySelector(".tour-main img");
    const thumbImgs = card.querySelectorAll(".tour-thumbs .thumb-item img");

    // Mark first thumb as active if exists
    if (thumbImgs && thumbImgs.length) {
      card.querySelectorAll(".tour-thumbs .thumb-item").forEach(function (el) {
        el.classList.remove("active");
      });
    }

    thumbImgs.forEach(function (img) {
      img.addEventListener("click", function (e) {
        const idx = parseInt(this.dataset.index, 10);
        if (!isNaN(idx) && gallery[idx]) {
          if (mainImg) mainImg.src = gallery[idx];
          card.dataset.currentIndex = idx;
        } else if (mainImg) {
          mainImg.src = this.src;
          card.dataset.currentIndex = this.dataset.index || 0;
        }
        // active state
        card.querySelectorAll(".tour-thumbs .thumb-item").forEach(function (t) {
          t.classList.remove("active");
        });
        this.parentElement.classList.add("active");
      });
    });

    if (mainImg) {
      mainImg.style.cursor = "zoom-in";
      mainImg.addEventListener("click", function () {
        let idx = parseInt(card.dataset.currentIndex || "0", 10);
        if (isNaN(idx)) idx = 0;
        lightbox.open(gallery.length ? gallery : [this.src], idx);
      });
    }
  });

  // ===== Edit page: connect previews to lightbox =====
  const previewContainer = document.getElementById("image-preview-container");
  if (previewContainer) {
    // Open lightbox when clicking the preview image or the action button in overlay
    previewContainer.addEventListener("click", function (e) {
      // Prefer detecting the action button element (more robust than icon-only selector)
      const actionBtn = e.target.closest(".action-btn");
      if (actionBtn) {
        // If it's the eye button (view)
        if (actionBtn.classList.contains("fa-eye")) {
          e.preventDefault();
          const wrappers = Array.from(previewContainer.children);
          const imgs = Array.from(
            previewContainer.querySelectorAll("img.card-img-top")
          ).map((i) => i.src);
          const wrapper = actionBtn.closest(".col-6, .col-md-4, .col-lg-3");
          let idx = 0;
          if (wrapper) {
            idx = wrappers.indexOf(wrapper);
            if (idx === -1) idx = 0;
          }
          if (window.tourLightbox) {
            window.tourLightbox.open(imgs, idx);
          } else {
            console.warn("tourLightbox not available yet");
          }
          return;
        }
      }

      // If click directly on the preview image
      const imgEl = e.target.closest("img.card-img-top");
      if (imgEl) {
        e.preventDefault();
        const imgs = Array.from(
          previewContainer.querySelectorAll("img.card-img-top")
        ).map((i) => i.src);
        const wrappers = Array.from(previewContainer.children);
        const wrapper = imgEl.closest(".col-6, .col-md-4, .col-lg-3");
        let idx = wrappers.indexOf(wrapper);
        if (idx === -1) idx = imgs.indexOf(imgEl.src) || 0;
        if (window.tourLightbox) {
          window.tourLightbox.open(imgs, idx);
        } else {
          console.warn("tourLightbox not available yet");
        }
      }
    });
  }

  // ===== TOURS CREATE/EDIT SECTION (no CKEditor) =====

  // Attach form submit handler to serialize dynamic sections into hidden inputs.
  (function attachTourFormHandler() {
    const tourForms = document.querySelectorAll("form.tour-form");
    tourForms.forEach(function (form) {
      form.addEventListener("submit", function (e) {
        try {
          // pricing
          const pricingList = document.getElementById("pricing-tier-list");
          const pricingArr = [];
          if (pricingList) {
            pricingList
              .querySelectorAll(".pricing-tier-item")
              .forEach(function (item) {
                const obj = {};
                item.querySelectorAll("[data-field]").forEach(function (f) {
                  const key = f.dataset.field;
                  if (!key) return;
                  obj[key] = f.value;
                });
                if (Object.keys(obj).length) pricingArr.push(obj);
              });
          }

          // itinerary
          const itinList = document.getElementById("itinerary-list");
          const itinArr = [];
          if (itinList) {
            itinList
              .querySelectorAll(".itinerary-item")
              .forEach(function (item) {
                const obj = {};
                item.querySelectorAll("[data-field]").forEach(function (f) {
                  const key = f.dataset.field;
                  if (!key) return;
                  obj[key] = f.value;
                });
                if (Object.keys(obj).length) itinArr.push(obj);
              });
          }

          // partners
          const partnerList = document.getElementById("partner-list");
          const partnerArr = [];
          if (partnerList) {
            partnerList
              .querySelectorAll(".partner-item")
              .forEach(function (item) {
                const obj = {};
                item.querySelectorAll("[data-field]").forEach(function (f) {
                  const key = f.dataset.field;
                  if (!key) return;
                  obj[key] = f.value;
                });
                if (Object.keys(obj).length) partnerArr.push(obj);
              });
          }

          // attach hidden inputs (replace if exist)
          function upsertHidden(name, value) {
            let input = form.querySelector('input[name="' + name + '"]');
            if (!input) {
              input = document.createElement("input");
              input.type = "hidden";
              input.name = name;
              form.appendChild(input);
            }
            input.value = value;
          }

          upsertHidden("tour_pricing_options", JSON.stringify(pricingArr));
          upsertHidden("tour_itinerary", JSON.stringify(itinArr));
          upsertHidden("tour_partners", JSON.stringify(partnerArr));
        } catch (err) {
          console.error("Error serializing dynamic sections:", err);
        }
      });
    });
  })();

  function setupDynamicSection(config) {
    var listEl = document.getElementById(config.listId);
    var template = document.getElementById(config.templateId);
    var addBtn = document.getElementById(config.addBtnId);

    if (!listEl || !template || !addBtn) {
      return;
    }

    function resetItem(item) {
      item.querySelectorAll("[data-field]").forEach(function (field) {
        if (field.tagName === "SELECT") {
          field.selectedIndex = 0;
        } else {
          field.value = "";
        }
      });
    }

    function bindRemove(item) {
      var removeBtn = item.querySelector(config.removeSelector);
      if (!removeBtn) return;

      removeBtn.addEventListener("click", function () {
        var items = listEl.querySelectorAll("." + config.itemClass);
        if (items.length > 1) {
          item.remove();
        } else {
          resetItem(item);
        }
      });
    }

    function hydrateItem(item, values) {
      if (!values) return;
      item.querySelectorAll("[data-field]").forEach(function (field) {
        var key = field.dataset.field;
        if (!key) return;
        var value = values[key];
        if (value === undefined || value === null) return;
        if (field.tagName === "SELECT") {
          field.value = value;
        } else {
          field.value = value;
        }
      });
    }

    function appendNewItem(values) {
      var clone = template.content.cloneNode(true);
      var item =
        clone.querySelector("." + config.itemClass) || clone.children[0];
      hydrateItem(item, values);
      listEl.appendChild(item);
      bindRemove(item);
    }

    var existingItems = listEl.querySelectorAll("." + config.itemClass);
    if (existingItems.length) {
      existingItems.forEach(bindRemove);
    } else {
      var initialData = [];
      try {
        initialData = listEl.dataset.initial
          ? JSON.parse(listEl.dataset.initial)
          : [];
      } catch (error) {
        initialData = [];
      }
      if (initialData.length) {
        initialData.forEach(function (data) {
          appendNewItem(data);
        });
      } else {
        appendNewItem();
      }
    }

    addBtn.addEventListener("click", function () {
      appendNewItem();
    });
  }

  setupDynamicSection({
    listId: "pricing-tier-list",
    templateId: "pricing-tier-template",
    addBtnId: "add-pricing-tier",
    removeSelector: ".remove-pricing-tier",
    itemClass: "pricing-tier-item",
  });

  setupDynamicSection({
    listId: "itinerary-list",
    templateId: "itinerary-item-template",
    addBtnId: "add-itinerary-item",
    removeSelector: ".remove-itinerary-item",
    itemClass: "itinerary-item",
  });

  setupDynamicSection({
    listId: "partner-list",
    templateId: "partner-item-template",
    addBtnId: "add-partner-item",
    removeSelector: ".remove-partner-item",
    itemClass: "partner-item",
  });

  var imageInput = document.getElementById("image");
  if (imageInput) {
    imageInput.addEventListener("change", function (e) {
      var file = e.target.files[0];
      if (!file) return;

      var reader = new FileReader();
      reader.onload = function (event) {
        var preview = document.createElement("img");
        preview.src = event.target.result;
        preview.className = "form-image-preview";

        var previewContainer = document.getElementById("image-preview");
        if (previewContainer) {
          previewContainer.innerHTML = "";
          previewContainer.appendChild(preview);
        }
      };
      reader.readAsDataURL(file);
    });
  }

  var galleryInput = document.getElementById("gallery");
  if (galleryInput) {
    galleryInput.addEventListener("change", function (e) {
      var files = Array.from(e.target.files || []);
      var previewGrid = document.getElementById("gallery-preview");
      if (!previewGrid) return;
      previewGrid.innerHTML = "";

      files.slice(0, 10).forEach(function (file) {
        if (!file.type.startsWith("image/")) return;
        var reader = new FileReader();
        reader.onload = function (evt) {
          var col = document.createElement("div");
          col.className = "col-4";
          col.innerHTML =
            '<div class="ratio ratio-4x3 rounded overflow-hidden border"><img src="' +
            evt.target.result +
            '" class="w-100 h-100 object-fit-cover" alt=""></div>';
          previewGrid.appendChild(col);
        };
        reader.readAsDataURL(file);
      });
    });
  }
});
