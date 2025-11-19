document.addEventListener("DOMContentLoaded", function () {
  function initQuillEditor(selector, inputId) {
    var element = document.querySelector(selector);
    if (!element || typeof Quill === "undefined") {
      return null;
    }
    var editor = new Quill(selector, {
      theme: "snow",
      modules: {
        toolbar: [
          ["bold", "italic", "underline", "strike"],
          ["blockquote", "code-block"],
          [
            {
              list: "ordered",
            },
            {
              list: "bullet",
            },
          ],
          [
            {
              size: ["small", false, "large", "huge"],
            },
          ],
          [
            {
              header: [1, 2, 3, 4, 5, 6],
            },
          ],
          ["link"],
          ["clean"],
        ],
      },
    });

    var hiddenInput = document.getElementById(inputId);
    if (hiddenInput && hiddenInput.value) {
      editor.root.innerHTML = hiddenInput.value;
    }

    return {
      instance: editor,
      sync: function () {
        if (hiddenInput) {
          hiddenInput.value = editor.root.innerHTML;
        }
      },
    };
  }

  var descriptionEditor = initQuillEditor(
    "#editor-description",
    "input-description"
  );
  var policyEditor = initQuillEditor("#editor-policy", "input-policy");

  var tourForms = document.querySelectorAll("form.tour-form");
  tourForms.forEach(function (form) {
    form.addEventListener("submit", function () {
      if (descriptionEditor) {
        descriptionEditor.sync();
      }
      if (policyEditor) {
        policyEditor.sync();
      }
    });
  });

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
