<?php include PATH_VIEW_ADMIN . '/default/header.php'; ?>

<button><a href="<?= BASE_URL_ADMIN ?>&action=logout" class="d-flex align-items-center text-white text-decoration-none" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
        <strong>Đăng xuất</strong>
    </a></button>
<?php include PATH_VIEW_ADMIN . '/default/footer.php'; ?>