document.addEventListener("DOMContentLoaded", function () {
  const filterForm = document.getElementById("filter-form");
  const applyBtn = document.getElementById("apply-btn");
  const resetBtn = document.getElementById("reset-btn");
  const tourListContainer = document.getElementById("tour-list-container");
  const baseUrl = `${window.location.pathname}?action=tours`;

  if (!filterForm || !tourListContainer) {
    return;
  }

  const showLoading = () => {
    tourListContainer.style.opacity = "0.5";
    tourListContainer.style.pointerEvents = "none";
  };

  const hideLoading = () => {
    tourListContainer.style.opacity = "1";
    tourListContainer.style.pointerEvents = "auto";
  };

  const loadTours = async (url) => {
    showLoading();
    try {
      const response = await fetch(url, {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      });
      const html = await response.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");
      const newContent = doc.getElementById("tour-list-container");

      if (newContent) {
        tourListContainer.innerHTML = newContent.innerHTML;
        // Re-attach event listeners after content update
        attachPaginationListeners();
      } else {
        console.error("Failed to parse response");
        alert("Đã có lỗi xảy ra. Vui lòng tải lại trang.");
      }

      history.pushState({ path: url }, "", url);
    } catch (error) {
      console.error("Failed to load tours:", error);
      alert("Đã có lỗi xảy ra. Vui lòng tải lại trang.");
    } finally {
      hideLoading();
    }
  };

  const attachPaginationListeners = () => {
    const ajaxLinks = tourListContainer.querySelectorAll("a.ajax-link");
    ajaxLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        const url = this.getAttribute("href");
        if (url) {
          loadTours(url);
        }
      });
    });
  };

  // Handle apply button click - collect filters and load without page reload
  if (applyBtn) {
    applyBtn.addEventListener("click", function (e) {
      e.preventDefault();

      // Get current filter values
      const keyword = document.querySelector('input[name="keyword"]').value;
      const type = document.querySelector('select[name="type"]').value;
      const supplierId = document.querySelector(
        'select[name="supplier_id"]'
      ).value;
      const perPage = document.querySelector('select[name="per_page"]').value;
      const sortBy = document.querySelector('select[name="sort_by"]').value;
      const sortDir = document.querySelector('select[name="sort_dir"]').value;

      // Build URL with filter parameters
      const params = new URLSearchParams();
      if (keyword) params.append("keyword", keyword);
      if (type) params.append("type", type);
      if (supplierId) params.append("supplier_id", supplierId);
      if (perPage) params.append("per_page", perPage);
      if (sortBy) params.append("sort_by", sortBy);
      if (sortDir) params.append("sort_dir", sortDir);

      const url = `${baseUrl}&${params.toString()}`;
      loadTours(url);
    });
  }

  // Handle reset button - clear filters and reload default view
  if (resetBtn) {
    resetBtn.addEventListener("click", function (e) {
      e.preventDefault();

      // Clear all filter inputs
      document.querySelector('input[name="keyword"]').value = "";
      document.querySelector('select[name="type"]').value = "";
      document.querySelector('select[name="supplier_id"]').value = "";
      document.querySelector('select[name="per_page"]').value = "10";
      document.querySelector('select[name="sort_by"]').value = "created_at";
      document.querySelector('select[name="sort_dir"]').value = "desc";

      // Load default view without filters
      loadTours(baseUrl);
    });
  }

  // Initial pagination listeners
  attachPaginationListeners();

  // Handle back/forward browser buttons
  window.addEventListener("popstate", function (e) {
    if (e.state && e.state.path) {
      loadTours(e.state.path);
    } else {
      loadTours(baseUrl);
    }
  });
});
