<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Quản lý Tour</h1>
                <p class="text-muted">Toàn bộ các tour đang được quản lý trên hệ thống.</p>
            </div>
            <a href="<?= BASE_URL_ADMIN . '&action=tours/create' ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm Tour Mới</a>
        </div>

        <!-- Statistics Header -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="stats-title">Tổng Tour</h6>
                            <h4 class="stats-value mb-0"><?= number_format($stats['total_tours'] ?? 0) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="stats-title">Tour Đang Hoạt Động</h6>
                            <h4 class="stats-value mb-0"><?= number_format($stats['active_tours'] ?? 0) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="stats-title">Tổng Đặt Tour</h6>
                            <h4 class="stats-value mb-0"><?= number_format($stats['total_bookings'] ?? 0) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="stats-title">Đánh Giá Trung Bình</h6>
                            <h4 class="stats-value mb-0"><?= number_format($stats['avg_rating'] ?? 0, 1) ?> <small class="text-muted">/5</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc & Tìm kiếm</h6>
            </div>
            <div class="card-body">
                <form id="tour-filters" method="GET" action="<?= BASE_URL_ADMIN . '&action=tours' ?>">
                    <input type="hidden" name="action" value="tours">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" name="keyword" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" placeholder="Tên tour, mô tả...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Loại Tour</label>
                            <select class="form-select" name="category_id">
                                <option value="">Tất cả</option>
                                <?php foreach ($categories ?? [] as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= (($_GET['category_id'] ?? '') == $category['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Nhà Cung Cấp</label>
                            <select class="form-select" name="supplier_id">
                                <option value="">Tất cả</option>
                                <?php foreach ($suppliers ?? [] as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>" <?= (($_GET['supplier_id'] ?? '') == $supplier['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($supplier['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá từ (VNĐ)</label>
                            <input type="number" class="form-control" name="price_min" value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>" placeholder="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá đến (VNĐ)</label>
                            <input type="number" class="form-control" name="price_max" value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>" placeholder="Không giới hạn">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Sao ≥</label>
                            <select class="form-select" name="rating_min">
                                <option value="">Tất cả</option>
                                <option value="1" <?= (($_GET['rating_min'] ?? '') == '1') ? 'selected' : '' ?>>1 sao</option>
                                <option value="2" <?= (($_GET['rating_min'] ?? '') == '2') ? 'selected' : '' ?>>2 sao</option>
                                <option value="3" <?= (($_GET['rating_min'] ?? '') == '3') ? 'selected' : '' ?>>3 sao</option>
                                <option value="4" <?= (($_GET['rating_min'] ?? '') == '4') ? 'selected' : '' ?>>4 sao</option>
                                <option value="5" <?= (($_GET['rating_min'] ?? '') == '5') ? 'selected' : '' ?>>5 sao</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-2">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sắp xếp theo</label>
                            <select class="form-select" name="sort_by">
                                <option value="">Mặc định</option>
                                <option value="name" <?= (($_GET['sort_by'] ?? '') == 'name') ? 'selected' : '' ?>>Tên tour</option>
                                <option value="price" <?= (($_GET['sort_by'] ?? '') == 'price') ? 'selected' : '' ?>>Giá</option>
                                <option value="rating" <?= (($_GET['sort_by'] ?? '') == 'rating') ? 'selected' : '' ?>>Đánh giá</option>
                                <option value="created_at" <?= (($_GET['sort_by'] ?? '') == 'created_at') ? 'selected' : '' ?>>Ngày tạo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Thứ tự</label>
                            <select class="form-select" name="sort_dir">
                                <option value="DESC" <?= (($_GET['sort_dir'] ?? '') == 'DESC') ? 'selected' : '' ?>>Giảm dần</option>
                                <option value="ASC" <?= (($_GET['sort_dir'] ?? '') == 'ASC') ? 'selected' : '' ?>>Tăng dần</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hiển thị</label>
                            <select class="form-select" name="per_page">
                                <option value="12" <?= (($_GET['per_page'] ?? '') == '12') ? 'selected' : '' ?>>12</option>
                                <option value="24" <?= (($_GET['per_page'] ?? '') == '24') ? 'selected' : '' ?>>24</option>
                                <option value="48" <?= (($_GET['per_page'] ?? '') == '48') ? 'selected' : '' ?>>48</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="<?= BASE_URL_ADMIN . '&action=tours' ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Xóa lọc
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tours Grid -->
        <div id="tour-list-container">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Danh sách Tour</span>
                    <small class="text-muted">
                        <?= $pagination['total'] ?? 0 ?> tour • Trang <?= $pagination['page'] ?? 1 ?>/<?= max(1, $pagination['total_pages'] ?? 1) ?>
                    </small>
                </div>
                <div class="card-body">
                    <?php if (!empty($tours)) : ?>
                        <div class="tour-grid">
                            <?php foreach ($tours as $tour) : ?>
                                <div class="tour-card">
                                    <div class="tour-image">
                                        <?php
                                        $imageUrl = $tour['main_image'] ?: 'assets/admin/image/no-image.png';
                                        $imagePath = PATH_ROOT . $imageUrl;
                                        if (!file_exists($imagePath) || empty($tour['main_image'])) {
                                            $imageUrl = 'assets/admin/image/no-image.png';
                                        }
                                        ?>
                                        <img src="<?= BASE_URL . $imageUrl ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="img-fluid">
                                        <div class="tour-overlay">
                                            <div class="tour-actions">
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours/detail&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-light me-1" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours/edit&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-light me-1" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours/delete&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa tour này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tour-content">
                                        <h6 class="tour-title"><?= htmlspecialchars($tour['name']) ?></h6>
                                        <div class="tour-meta">
                                            <span class="badge bg-primary mb-2"><?= htmlspecialchars($tour['category_name']) ?></span>
                                            <div class="tour-supplier">
                                                <i class="fas fa-building me-1"></i>
                                                <?= htmlspecialchars($tour['supplier_name'] ?: '---') ?>
                                            </div>
                                        </div>
                                        <div class="tour-stats">
                                            <div class="stat-item">
                                                <i class="fas fa-star text-warning"></i>
                                                <span><?= number_format($tour['avg_rating'], 1) ?></span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-calendar-check text-info"></i>
                                                <span><?= number_format($tour['booking_count']) ?></span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-percentage text-success"></i>
                                                <span><?= number_format($tour['availability_percentage'], 1) ?>%</span>
                                            </div>
                                        </div>
                                        <div class="tour-price">
                                            <strong><?= number_format($tour['base_price'], 0, ',', '.') ?> VNĐ</strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không tìm thấy tour phù hợp</h5>
                            <p class="text-muted">Thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                        </div>
                    <?php endif; ?>

                    <?php if (($pagination['total_pages'] ?? 1) > 1) : ?>
                        <?php
                        $filterParams = array_filter([
                            'keyword' => $_GET['keyword'] ?? '',
                            'category_id' => $_GET['category_id'] ?? '',
                            'supplier_id' => $_GET['supplier_id'] ?? '',
                            'date_from' => $_GET['date_from'] ?? '',
                            'date_to' => $_GET['date_to'] ?? '',
                            'price_min' => $_GET['price_min'] ?? '',
                            'price_max' => $_GET['price_max'] ?? '',
                            'rating_min' => $_GET['rating_min'] ?? '',
                            'per_page' => $pagination['per_page'] ?? null,
                            'sort_by' => $_GET['sort_by'] ?? null,
                            'sort_dir' => $_GET['sort_dir'] ?? null,
                        ], function ($value) {
                            return $value !== null && $value !== '';
                        });

                        $queryStringBase = '';
                        if (!empty($filterParams)) {
                            $queryStringBase = '&' . http_build_query($filterParams);
                        }
                        ?>

                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php
                                $currentPage = $pagination['page'];
                                $totalPages = $pagination['total_pages'];
                                $prevPage = max(1, $currentPage - 1);
                                $nextPage = min($totalPages, $currentPage + 1);
                                ?>

                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $prevPage . $queryStringBase ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>

                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);

                                if ($startPage > 1) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= BASE_URL_ADMIN . '&action=tours&page=1' . $queryStringBase ?>">1</a>
                                    </li>
                                    <?php if ($startPage > 2) : ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($page = $startPage; $page <= $endPage; $page++) : ?>
                                    <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $page . $queryStringBase ?>"><?= $page ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages) : ?>
                                    <?php if ($endPage < $totalPages - 1) : ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $totalPages . $queryStringBase ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>

                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $nextPage . $queryStringBase ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= BASE_ASSETS_ADMIN ?>js/tours.js"></script>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
