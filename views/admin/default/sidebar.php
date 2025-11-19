<?php
$action = $_GET['action'] ?? '';
$primaryAction = explode('/', $action)[0] ?? '';
$primaryAction = $primaryAction !== '' ? $primaryAction : 'dashboard';

$navItems = [
    'dashboard' => [
        'label' => 'Tổng quan',
        'icon' => 'fas fa-tachometer-alt',
        'href' => BASE_URL_ADMIN
    ],
    'tours' => [
        'label' => 'Quản lý Tour',
        'icon' => 'fas fa-map-signs',
        'href' => BASE_URL_ADMIN . '&action=tours'
    ],
    'bookings' => [
        'label' => 'Quản lý Booking',
        'icon' => 'fas fa-calendar-check',
        'href' => BASE_URL_ADMIN . '&action=bookings'
    ],
    'guides' => [
        'label' => 'Quản lý HDV',
        'icon' => 'fas fa-user-tie',
        'href' => BASE_URL_ADMIN . '&action=guides'
    ],
    'departures' => [
        'label' => 'Lịch khởi hành',
        'icon' => 'fas fa-calendar-alt',
        'href' => '#'
    ],
    'reports' => [
        'label' => 'Báo cáo',
        'icon' => 'fas fa-chart-line',
        'href' => '#'
    ],
    'settings' => [
        'label' => 'Cài đặt',
        'icon' => 'fas fa-cogs',
        'href' => '#'
    ]
];
?>

<aside class="sidebar vh-100 border-end bg-light">
    <div class="p-3">
        <h4 class="fw-bold text-center">Tour Admin</h4>
    </div>
    <ul class="nav flex-column">
        <?php foreach ($navItems as $key => $item): ?>
            <?php
            $isActive = $primaryAction === $key;
            $linkClasses = 'nav-link d-flex align-items-center gap-2';
            if ($isActive) {
                $linkClasses .= ' active';
            }
            ?>
            <li class="nav-item">
                <a class="<?= $linkClasses ?>" href="<?= $item['href'] ?>" <?= $isActive ? 'aria-current="page"' : '' ?>>
                    <i class="<?= $item['icon'] ?> fa-fw"></i>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>