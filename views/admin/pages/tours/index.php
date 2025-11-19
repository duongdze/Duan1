<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <style>
            .sort-indicator {
                font-size: 0.75rem;
                min-width: 26px;
                display: inline-flex;
                justify-content: center;
                align-items: center;
                border-radius: 999px;
                background-color: #f1f5f9;
                color: #64748b;
                padding: 0 0.35rem;
            }

            .sort-indicator.active {
                background-color: #0d6efd;
                color: #fff;
            }
        </style>
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Quản lý Tour</h1>
                <p class="text-muted">Toàn bộ các tour đang được quản lý trên hệ thống.</p>
            </div>
            <a href="<?= BASE_URL_ADMIN . '&action=tours/create' ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm Tour Mới</a>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc</h5>
            </div>
            <div class="card-body">
                <form class="row g-3 align-items-end" method="GET" action="<?= BASE_URL_ADMIN ?>">
                    <input type="hidden" name="action" value="tours">
                    <div class="col-md-3">
                        <label class="form-label fw-500">Từ khóa</label>
                        <input type="text" class="form-control" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Tên tour, điểm đến...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-500">Loại tour</label>
                        <select class="form-select" name="type">
                            <option value="">Tất cả</option>
                            <option value="trong_nuoc" <?= ($filters['type'] ?? '') === 'trong_nuoc' ? 'selected' : '' ?>>Trong nước</option>
                            <option value="quoc_te" <?= ($filters['type'] ?? '') === 'quoc_te' ? 'selected' : '' ?>>Quốc tế</option>
                            <option value="theo_yeu_cau" <?= ($filters['type'] ?? '') === 'theo_yeu_cau' ? 'selected' : '' ?>>Theo yêu cầu</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-500">Nhà cung cấp</label>
                        <select class="form-select" name="supplier_id">
                            <option value="">Tất cả</option>
                            <?php foreach (($suppliers ?? []) as $supplier): ?>
                                <option value="<?= htmlspecialchars($supplier['id']) ?>" <?= ($filters['supplier_id'] ?? '') == $supplier['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($supplier['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-500">Từ ngày</label>
                        <input type="date" class="form-control" name="date_from" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-500">Đến ngày</label>
                        <input type="date" class="form-control" name="date_to" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-500">Hiển thị</label>
                        <select class="form-select" name="per_page">
                            <?php foreach ([10, 20, 30, 50] as $size): ?>
                                <option value="<?= $size ?>" <?= ($pagination['per_page'] ?? 10) == $size ? 'selected' : '' ?>><?= $size ?> tour/trang</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-search"></i> Lọc</button>
                        <a class="btn btn-outline-secondary" href="<?= BASE_URL_ADMIN . '&action=tours' ?>"><i class="fas fa-undo"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-sort me-2"></i>Sắp xếp</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="add-sort-rule">
                        <i class="fas fa-plus"></i> Thêm tiêu chí
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-sort">
                        <i class="fas fa-undo"></i> Mặc định
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Thêm tối đa 3 tiêu chí</p>
                <div id="sort-rules" class="d-flex flex-column gap-3"></div>
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-outline-primary" id="apply-sort">
                        <i class="fas fa-sort-amount-up"></i> Áp dụng sắp xếp
                    </button>
                </div>
                <template id="sort-rule-template">
                    <div class="sort-rule border rounded p-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-500">Cột</label>
                                <select class="form-select" data-field="column">
                                    <option value="name">Tên Tour</option>
                                    <option value="type">Loại Tour</option>
                                    <option value="supplier">Nhà cung cấp</option>
                                    <option value="created_at">Ngày tạo</option>
                                    <option value="base_price">Giá cơ bản</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-500">Thứ tự</label>
                                <select class="form-select" data-field="direction">
                                    <option value="asc">Tăng dần</option>
                                    <option value="desc">Giảm dần</option>
                                </select>
                            </div>
                            <div class="col-md-3 text-end">
                                <button type="button" class="btn btn-link text-danger remove-sort-rule">
                                    <i class="fas fa-times"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Danh sách Tour</span>
                    <small class="text-muted">
                        <?= $pagination['total'] ?? 0 ?> tour • Trang <?= $pagination['page'] ?? 1 ?>/<?= max(1, $pagination['total_pages'] ?? 1) ?>
                    </small>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($tours)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tour-table">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">
                                        <span>Tên Tour</span>
                                        <span class="sort-indicator ms-2" data-col="name"></span>
                                    </th>
                                    <th class="text-nowrap">
                                        <span>Loại Tour</span>
                                        <span class="sort-indicator ms-2" data-col="type"></span>
                                    </th>
                                    <th class="text-nowrap">
                                        <span>Nhà cung cấp</span>
                                        <span class="sort-indicator ms-2" data-col="supplier"></span>
                                    </th>
                                    <th class="text-nowrap">
                                        <span>Ngày tạo</span>
                                        <span class="sort-indicator ms-2" data-col="created_at"></span>
                                    </th>
                                    <th class="text-nowrap">
                                        <span>Giá cơ bản</span>
                                        <span class="sort-indicator ms-2" data-col="base_price"></span>
                                    </th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $normalizeValue = function ($value) {
                                    $value = (string)$value;
                                    return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
                                };
                                ?>
                                <?php foreach ($tours as $tour) :
                                    $supplierName = $tour['supplier_name'] ?? '';
                                    $createdTimestamp = strtotime($tour['created_at']);
                                ?>
                                    <tr
                                        data-sort-name="<?= htmlspecialchars($normalizeValue($tour['name'] ?? '')) ?>"
                                        data-sort-type="<?= htmlspecialchars($normalizeValue($tour['type'] ?? '')) ?>"
                                        data-sort-supplier="<?= htmlspecialchars($normalizeValue($supplierName ?? '')) ?>"
                                        data-sort-created_at="<?= $createdTimestamp ?>"
                                        data-sort-base_price="<?= (float)$tour['base_price'] ?>">
                                        <td class="fw-500" data-col="name"><?= htmlspecialchars($tour['name']) ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <span data-col="type"><?= htmlspecialchars($tour['type']) ?></span>
                                            </span>
                                        </td>
                                        <td data-col="supplier"><?= htmlspecialchars($supplierName ?: '---') ?></td>
                                        <td data-col="created_at"><?= date('d/m/Y', $createdTimestamp) ?></td>
                                        <td data-col="base_price"><?= number_format((float)$tour['base_price'], 0, ',', '.') ?> VNĐ</td>
                                        <td class="d-flex gap-1">
                                            <a href="<?= BASE_URL_ADMIN . '&action=tours/detail&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=tours/edit&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=tours/delete&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <img src="https://cdn.jsdelivr.net/gh/edent/SuperTinyIcons/images/svg/airbnb.svg" alt="" width="48">
                        <p class="mt-3 mb-0 text-muted">Không tìm thấy tour phù hợp với bộ lọc hiện tại.</p>
                    </div>
                <?php endif; ?>

                <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
                    <?php
                    $filterParams = array_filter([
                        'keyword' => $filters['keyword'] ?? '',
                        'type' => $filters['type'] ?? '',
                        'supplier_id' => $filters['supplier_id'] ?? '',
                        'date_from' => $filters['date_from'] ?? '',
                        'date_to' => $filters['date_to'] ?? '',
                        'per_page' => $pagination['per_page'] ?? null,
                    ], function ($value) {
                        return $value !== null && $value !== '' && $value !== 0;
                    });

                    $queryStringBase = '';
                    if (!empty($filterParams)) {
                        $queryStringBase = '&' . http_build_query($filterParams);
                    }
                    ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-end">
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
                            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                                <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $page . $queryStringBase ?>"><?= $page ?></a>
                                </li>
                            <?php endfor; ?>
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
    <script src="<?= BASE_ASSETS_ADMIN ?>js/tours-index.js"></script>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>