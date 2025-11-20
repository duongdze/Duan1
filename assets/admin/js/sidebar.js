document.addEventListener('DOMContentLoaded', function () {
    // Lấy danh sách tất cả các dropdown toggle
    const dropdownToggles = document.querySelectorAll('.dropdown-toggles');

    dropdownToggles.forEach(toggle => {
        const menuKey = toggle.getAttribute('data-menu-key');
        const collapseId = toggle.getAttribute('data-collapse-id');
        const collapseEl = document.getElementById(collapseId);

        if (!collapseEl) return;

        // Kiểm tra xem hiện tại có đang ở trong menu này không
        const currentUrl = window.location.href;
        if (currentUrl.includes('action=' + menuKey)) {
            // Tự động mở dropdown
            const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });
            bsCollapse.show();
            toggle.setAttribute('aria-expanded', 'true');
        }

        // Xử lý sự kiện khi nhấp vào toggle
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: true });
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !isExpanded);
        });
    });
});