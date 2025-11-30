<?php
// Hàm helper để kiểm tra active
function isActive($action)
{
    $currentAction = $_GET['action'] ?? '';
    return ($currentAction == $action) ? 'active' : '';
}

// Hàm kiểm tra menu cha có active không (kiểm tra các menu con)
function isParentActive($prefix)
{
    $currentAction = $_GET['action'] ?? '';
    return (strpos($currentAction, $prefix) === 0) ? 'show' : '';
}

// Lấy action hiện tại
$currentAction = $_GET['action'] ?? '';
?>

<aside class="sidebar vh-100 border-end bg-light">
    <div class="p-3">
        <h4 class="fw-bold text-center">Tour Admin</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= empty($currentAction) ? 'active' : '' ?>" href="<?= BASE_URL_ADMIN ?>">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i> Tổng quan
            </a>
        </li>

        <!-- Phần Tour -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggles d-flex justify-content-between align-items-center <?= isParentActive('tours') ? 'active' : '' ?>"
                href="#" role="button"

                aria-expanded="<?= isParentActive('tours') ? 'true' : 'false' ?>"
                aria-controls="tourMenu"
                data-menu-key="tours"
                data-collapse-id="tourMenu">
                <span><i class="fas fa-map-signs fa-fw me-2"></i>Tour</span>
                <i class="fas fa-chevron-down fa-xs"></i>
            </a>
            <div class="collapse <?= isParentActive('tours') ?>" id="tourMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('tours') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours">
                            <i class="fas fa-list fa-fw me-2"></i> Danh sách Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('tours_categories') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours_categories">
                            <i class="fas fa-list fa-fw me-2"></i> Danh mục Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('tours_versions') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours_versions">
                            <i class="fas fa-list fa-fw me-2"></i> Phiên bản Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('tours_logs') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours_logs">
                            <i class="fas fa-book fa-fw me-2"></i> Nhật ký Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('tours_history') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours_history">
                            <i class="fas fa-history fa-fw me-2"></i> Lịch sử Tour
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Phần Booking -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggles d-flex justify-content-between align-items-center <?= isParentActive('bookings') ? 'active' : '' ?>"
                href="#" role="button"

                aria-expanded="<?= isParentActive('bookings') ? 'true' : 'false' ?>"
                aria-controls="bookingMenu"
                data-menu-key="bookings"
                data-collapse-id="bookingMenu">
                <span><i class="fas fa-calendar-check fa-fw me-2"></i>Booking</span>
                <i class="fas fa-chevron-down fa-xs"></i>
            </a>
            <div class="collapse <?= isParentActive('bookings') ?>" id="bookingMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('bookings') ?>" href="<?= BASE_URL_ADMIN ?>&action=bookings">
                            <i class="fas fa-list fa-fw me-2"></i> Quản lý Booking
                        </a>
                    </li>
                    <?php if (in_array($_SESSION['user']['role'] ?? '', ['guide', 'admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('guides/available-tours') ?>" href="<?= BASE_URL_ADMIN ?>&action=guides/available-tours">
                                <i class="fas fa-hand-paper fa-fw me-2"></i> Tour Khả Dụng
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>

        <!-- Phần HDV -->
        <li class="nav-item">
            <a class="nav-link <?= isActive('guides') ?>" href="<?= BASE_URL_ADMIN ?>&action=guides">
                <i class="fas fa-user-tie fa-fw me-2"></i> Quản lý HDV
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive('guide/schedule') ?>" href="<?= BASE_URL_ADMIN ?>&action=guide/schedule">
                <i class="fas fa-calendar-day fa-fw me-2"></i> Lịch làm việc của HDV
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive('schedule') ?>" href="<?= BASE_URL_ADMIN ?>&action=schedule">
                <i class="fas fa-calendar-alt fa-fw me-2"></i> Lịch khởi hành
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('reports') ?>" href="<?= BASE_URL_ADMIN ?>&action=reports">
                <i class="fas fa-chart-line fa-fw me-2"></i> Báo cáo
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('settings') ?>" href="<?= BASE_URL_ADMIN ?>&action=settings">
                <i class="fas fa-cogs fa-fw me-2"></i> Cài đặt
            </a>
        </li>

    </ul>
</aside>