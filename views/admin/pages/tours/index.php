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
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon" style="background-color: #e0f2fe;">
                            <i class="fas fa-route" style="color: #0ea5e9;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Tour</div>
                            <div class="kpi-value"><?= number_format($stats['total_tours'] ?? 0) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon" style="background-color: #dcfce7;">
                            <i class="fas fa-play-circle" style="color: #22c55e;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tour Đang Hoạt Động</div>
                            <div class="kpi-value"><?= number_format($stats['active_tours'] ?? 0) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon" style="background-color: #fefce8;">
                            <i class="fas fa-calendar-check" style="color: #eab308;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Đặt Tour</div>
                            <div class="kpi-value"><?= number_format($stats['total_bookings'] ?? 0) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon" style="background-color: #fee2e2;">
                            <i class="fas fa-star" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Đánh Giá Trung Bình</div>
                            <div class="kpi-value"><?= number_format($stats['avg_rating'] ?? 0, 1) ?> <small class="text-muted">/5</small></div>
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
                                <?php
                                // Prepare images
                                $galleryImages = [];
                                if (!empty($tour['gallery_images'])) {
                                    $galleryImages = array_values(array_filter(array_map('trim', explode(',', $tour['gallery_images']))));
                                }
                                if (empty($galleryImages) && !empty($tour['main_image'])) {
                                    $galleryImages = [$tour['main_image']];
                                }
                                $mainImage = $galleryImages[0] ?? ($tour['main_image'] ?? null);
                                $thumbs = array_slice($galleryImages, 1);
                                $totalImages = count($galleryImages);
                                $maxThumbs = 3;
                                $thumbsToShow = array_slice($thumbs, 0, $maxThumbs);
                                $remaining = max(0, $totalImages - 1 - count($thumbsToShow));

                                // Build full gallery URLs for JS (use JSON to safely pass array)
                                $galleryUrls = [];
                                if (!empty($galleryImages)) {
                                    foreach ($galleryImages as $g) {
                                        if (!empty($g)) {
                                            $galleryUrls[] = BASE_ASSETS_UPLOADS . $g;
                                        }
                                    }
                                }
                                if (empty($galleryUrls) && !empty($tour['main_image'])) {
                                    $galleryUrls = [BASE_ASSETS_UPLOADS . $tour['main_image']];
                                }
                                ?>

                                <div class="tour-card" data-gallery='<?= htmlspecialchars(json_encode($galleryUrls), ENT_QUOTES) ?>'>
                                    <div class="tour-card-inner">
                                        <div class="tour-gallery">
                                            <div class="tour-main">
                                                <?php if ($mainImage) :
                                                    $mainUrl = BASE_ASSETS_UPLOADS . $mainImage;
                                                else:
                                                    $mainUrl = BASE_URL . 'assets/admin/image/no-image.png';
                                                endif; ?>
                                                <img src="<?= $mainUrl ?>" alt="<?= htmlspecialchars($tour['name']) ?>" data-index="0">
                                                <span class="badge-top"><?= htmlspecialchars($tour['category_name'] ?? '') ?></span>
                                                <?php
                                                $price = $tour['base_price'] ?? 0;
                                                if ($price >= 1000000000) {
                                                    $priceShort = round($price / 1000000000, ($price / 1000000000) >= 10 ? 0 : 1) . ' tỷ';
                                                } elseif ($price >= 1000000) {
                                                    $priceShort = round($price / 1000000, 1) . ' tr';
                                                } else {
                                                    $priceShort = number_format($price, 0, ',', '.') . 'đ';
                                                }
                                                ?>
                                                <span class="price-badge"><?= $priceShort ?></span>
                                            </div>

                                            <?php if (!empty($thumbsToShow)) : ?>
                                                <div class="tour-thumbs">
                                                    <?php foreach ($thumbsToShow as $i => $timg):
                                                        $turl = !empty($timg) ? BASE_ASSETS_UPLOADS . $timg : BASE_URL . 'assets/admin/image/no-image.png';
                                                    ?>
                                                        <div class="thumb-item">
                                                            <img src="<?= $turl ?>" alt="thumb-<?= $i ?>" data-index="<?= $i + 1 ?>">
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <?php if ($remaining > 0) : ?>
                                                        <div class="thumb-item more">+<?= $remaining ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="tour-info">
                                            <div class="title-row">
                                                <h5 class="tour-title"><?= htmlspecialchars($tour['name']) ?></h5>
                                            </div>

                                            <div class="meta-row">
                                                <div class="rating"><i class="fas fa-star text-warning"></i> <?= number_format($tour['avg_rating'] ?? 0, 1) ?> <small class="text-muted">(<?= number_format($tour['booking_count'] ?? 0) ?>)</small></div>
                                                <div class="tour-price"><?= number_format($tour['base_price'] ?? 0, 0, ',', '.') ?> VNĐ</div>
                                            </div>

                                            <div class="detail-row">
                                                <div class="tour-date">
                                                    <i class="fas fa-calendar-alt icon-i" aria-hidden="true"></i>
                                                    <?= date('d/m/Y', strtotime($tour['created_at'] ?? 'now')) ?>
                                                </div>
                                            </div>

                                            <div class="tour-actions">
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours/detail&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-primary">Xem</a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours/edit&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-warning">Sửa</a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours/delete&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa tour này?')">Xóa</a>
                                            </div>
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