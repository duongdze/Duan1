<aside class="sidebar vh-100 border-end bg-light">
    <div class="p-3">
        <h4 class="fw-bold text-center">Tour Admin</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="<?= BASE_URL_ADMIN ?>">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i> Tổng quan
            </a>
        </li>
        <!-- Phần Tour -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggles d-flex justify-content-between align-items-center" href="#" role="button" aria-expanded="false" aria-controls="tourMenu" data-menu-key="tours" data-collapse-id="tourMenu">
                <span><i class="fas fa-map-signs fa-fw me-2"></i>Tour</span>
                <i class="fas fa-chevron-down fa-xs"></i>
            </a>
            <div class="collapse" id="tourMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL_ADMIN ?>&action=tours">
                            <i class="fas fa-list fa-fw me-2"></i> Danh sách Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL_ADMIN ?>&action=tour_logs">
                            <i class="fas bi-clock-history fa-fw me-2"></i> Lịch sử Tour
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <!-- Phần Booking -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggles d-flex justify-content-between align-items-center" href="#" role="button" aria-expanded="false" aria-controls="bookingMenu" data-menu-key="bookings" data-collapse-id="bookingMenu">
                <span><i class="fas fa-calendar-check fa-fw me-2"></i> Quản lý Booking</span>
                <i class="fas fa-chevron-down fa-xs"></i>
            </a>
            <div class="collapse" id="bookingMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL_ADMIN ?>&action=bookings">
                            <i class="fas fa-list fa-fw me-2"></i> Danh sách Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL_ADMIN ?>&action=bookings/create">
                            <i class="fas fa-plus-circle fa-fw me-2"></i> Thêm Booking Mới
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <!-- Phần HDV -->
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL_ADMIN ?>&action=guides">
                <i class="fas fa-user-tie fa-fw me-2"></i> Quản lý HDV
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-calendar-alt fa-fw me-2"></i> Lịch khởi hành
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-chart-line fa-fw me-2"></i> Báo cáo
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-cogs fa-fw me-2"></i> Cài đặt
            </a>
        </li>
    </ul>
</aside>