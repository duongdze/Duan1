<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$tour = $tour ?? null;
$allImages = $allImages ?? [];
$pricingOptions = $pricingOptions ?? [];
$itinerarySchedule = $itinerarySchedule ?? [];
$partnerServices = $partnerServices ?? [];

?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h2 mb-0"><?= htmlspecialchars($tour['name'] ?? 'Tour') ?></h1>
                <p class="text-muted small mb-0">Mã Tour: <?= htmlspecialchars($tour['id'] ?? '') ?></p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?= urlencode($tour['id'] ?? '') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=tours" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=tours/delete&id=<?= urlencode($tour['id'] ?? '') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa tour này không?');">
                    <i class="fas fa-trash"></i> Xóa
                </a>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <?php
            // prepare images and main image similar to list view
            $galleryImages = [];
            // $allImages may be an array of ['url' => '/uploads/...'] or full urls; normalize to relative paths when possible
            if (!empty($allImages)) {
                foreach ($allImages as $ai) {
                    $url = $ai['url'] ?? '';
                    if ($url === '') continue;
                    // if the stored url already contains the uploads base, strip it to get relative
                    if (strpos($url, BASE_ASSETS_UPLOADS) === 0) {
                        $galleryImages[] = substr($url, strlen(BASE_ASSETS_UPLOADS));
                    } else {
                        // if it's a full http url, keep it
                        $galleryImages[] = $url;
                    }
                }
            }

            // fallback: some controllers may provide main_image or gallery as comma-separated
            if (empty($galleryImages) && !empty($tour['gallery_images'])) {
                $parts = array_values(array_filter(array_map('trim', explode(',', $tour['gallery_images']))));
                foreach ($parts as $p) {
                    if ($p !== '') $galleryImages[] = $p;
                }
            }
            if (empty($galleryImages) && !empty($tour['main_image'])) {
                $galleryImages[] = $tour['main_image'];
            }

            $mainImage = $galleryImages[0] ?? null;
            $thumbs = array_slice($galleryImages, 1);
            $totalImages = count($galleryImages);
            $maxThumbs = 3;
            $thumbsToShow = array_slice($thumbs, 0, $maxThumbs);
            $remaining = max(0, $totalImages - 1 - count($thumbsToShow));

            // Build full gallery URLs for JS/lightbox
            $galleryUrls = [];
            foreach ($galleryImages as $g) {
                if (empty($g)) continue;
                if (strpos($g, 'http') === 0) {
                    $galleryUrls[] = $g;
                } else {
                    $galleryUrls[] = BASE_ASSETS_UPLOADS . $g;
                }
            }

            // formatted price like list view
            $price = $tour['base_price'] ?? 0;
            if ($price >= 1000000000) {
                $priceShort = round($price / 1000000000, ($price / 1000000000) >= 10 ? 0 : 1) . ' tỷ';
            } elseif ($price >= 1000000) {
                $priceShort = round($price / 1000000, 1) . ' tr';
            } else {
                $priceShort = number_format($price, 0, ',', '.') . 'đ';
            }
            $createdDate = !empty($tour['created_at']) ? date('d/m/Y', strtotime($tour['created_at'])) : '';
            $avgRating = number_format($tour['avg_rating'] ?? 0, 1);
            $bookingCount = (int)($tour['booking_count'] ?? 0);
            ?>
            <div class="col-lg-7">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="tour-card" data-gallery='<?= htmlspecialchars(json_encode($galleryUrls), ENT_QUOTES) ?>'>
                            <div class="tour-gallery">
                                <div class="tour-main mb-3 position-relative" style="height:500px;">
                                    <?php
                                    if ($mainImage) {
                                        $mainUrl = (strpos($mainImage, 'http') === 0) ? $mainImage : BASE_ASSETS_UPLOADS . $mainImage;
                                    } else {
                                        $mainUrl = BASE_URL . 'assets/admin/image/no-image.png';
                                    }
                                    ?>
                                    <img src="<?= $mainUrl ?>" alt="<?= htmlspecialchars($tour['name'] ?? '') ?>" class="img-fluid rounded" style="width:100%; height:500px; object-fit:cover;" data-index="0">
                                    <?php if (!empty($tour['category_name'])): ?>
                                        <span class="badge bg-primary position-absolute" style="top:10px; left:10px;"><?= htmlspecialchars($tour['category_name']) ?></span>
                                    <?php endif; ?>
                                    <span class="price-badge"><?= $priceShort ?></span>
                                </div>

                                <?php if (!empty($thumbsToShow)): ?>
                                    <div class="tour-thumbs d-flex gap-2 mb-3">
                                        <?php foreach ($thumbsToShow as $i => $timg):
                                            $turl = (strpos($timg, 'http') === 0) ? $timg : BASE_ASSETS_UPLOADS . $timg;
                                        ?>
                                            <div class="thumb-item" style="width:72px; height:72px; overflow:hidden; border-radius:6px;">
                                                <img src="<?= $turl ?>" alt="thumb-<?= $i ?>" style="width:100%; height:100%; object-fit:cover;" data-index="<?= $i + 1 ?>">
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if ($remaining > 0): ?>
                                            <div class="thumb-item d-flex align-items-center justify-content-center bg-secondary text-white" style="width:72px; height:72px; border-radius:6px;">+<?= $remaining ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <h5 class="mb-2">Mô tả</h5>
                        <div class="mb-3">
                            <?php if (!empty($tour['description'])): ?>
                                <div class="border rounded p-3 bg-white"><?= $tour['description'] ?></div>
                            <?php else: ?>
                                <div class="text-muted">Không có mô tả.</div>
                            <?php endif; ?>
                        </div>

                        <h5 class="mb-2">Chính sách</h5>
                        <div class="mb-3">
                            <?php if (!empty($tour['policy'])): ?>
                                <div class="border rounded p-3 bg-white"><?= $tour['policy'] ?></div>
                            <?php else: ?>
                                <div class="text-muted">Không có chính sách.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Lịch trình</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($itinerarySchedule)): ?>
                            <?php foreach ($itinerarySchedule as $it): ?>
                                <div class="mb-3">
                                    <h6 class="mb-1"><?= htmlspecialchars($it['day_label'] ?? ('Ngày ' . ($it['day_number'] ?? ''))) ?></h6>
                                    <div class="text-muted small mb-1"><?= htmlspecialchars($it['time_start'] ?? '') ?> - <?= htmlspecialchars($it['time_end'] ?? '') ?></div>
                                    <strong><?= htmlspecialchars($it['title'] ?? '') ?></strong>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($it['description'] ?? '')) ?></p>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-muted">Chưa có lịch trình.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <th class="w-50">Danh mục</th>
                                    <td><?= htmlspecialchars($tour['category_name'] ?? '') ?></td>
                                </tr>
                                <tr>
                                    <th>Nhà cung cấp</th>
                                    <td><?= htmlspecialchars($tour['supplier_name'] ?? '') ?></td>
                                </tr>
                                <tr>
                                    <th>Giá cơ bản</th>
                                    <td><?= number_format($tour['base_price'] ?? 0) ?> đ</td>
                                </tr>
                                <tr>
                                    <th>Đánh giá trung bình</th>
                                    <td><?= htmlspecialchars($tour['avg_rating'] ?? 0) ?></td>
                                </tr>
                                <tr>
                                    <th>Lượt đặt</th>
                                    <td><?= htmlspecialchars($tour['booking_count'] ?? 0) ?></td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td><?= htmlspecialchars($tour['created_at'] ?? '') ?></td>
                                </tr>
                                <tr>
                                    <th>Updated</th>
                                    <td><?= htmlspecialchars($tour['updated_at'] ?? '') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Gói giá</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pricingOptions)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($pricingOptions as $p): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-600"><?= htmlspecialchars($p['label'] ?? '') ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($p['description'] ?? '') ?></div>
                                        </div>
                                        <div class="ms-3 fw-700"><?= number_format($p['price'] ?? 0) ?> đ</div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-muted">Chưa có gói giá.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Đối tác dịch vụ</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($partnerServices)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($partnerServices as $ps): ?>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div class="fw-600"><?= htmlspecialchars($ps['partner_name'] ?? '') ?> <small class="text-muted">(<?= htmlspecialchars($ps['service_type'] ?? '') ?>)</small></div>
                                                <div class="small text-muted"><?= htmlspecialchars($ps['contact'] ?? '') ?></div>
                                                <div class="small mt-1"><?= nl2br(htmlspecialchars($ps['notes'] ?? '')) ?></div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-muted">Chưa có đối tác liên kết.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>