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
                <div class="card mb-3 shadow-sm">
                    <?php // attach same data-gallery JSON used in listing so shared JS works 
                    ?>
                    <div class="tour-card" data-gallery='<?= htmlspecialchars(json_encode($galleryUrls), ENT_QUOTES) ?>'>
                        <div class="card-body p-0">
                            <div class="tour-main position-relative" style="height:520px;">
                                <?php
                                if ($mainImage) {
                                    $mainUrl = (strpos($mainImage, 'http') === 0) ? $mainImage : BASE_ASSETS_UPLOADS . $mainImage;
                                } else {
                                    $mainUrl = BASE_URL . 'assets/admin/image/no-image.png';
                                }
                                ?>
                                <img src="<?= $mainUrl ?>" alt="<?= htmlspecialchars($tour['name'] ?? '') ?>" class="img-fluid w-100" style="height:520px; object-fit:cover;">
                                <div class="position-absolute" style="left:18px; bottom:18px;">
                                    <h3 class="text-white mb-1" style="text-shadow:0 2px 8px rgba(0,0,0,.6);"><?= htmlspecialchars($tour['name'] ?? '') ?></h3>
                                    <div class="text-white small" style="text-shadow:0 2px 6px rgba(0,0,0,.6);">
                                        <?= htmlspecialchars($tour['subtitle'] ?? ($tour['short_description'] ?? '')) ?>
                                    </div>
                                </div>
                                <div class="position-absolute" style="right:18px; top:18px;">
                                    <div class="badge bg-primary"><?= htmlspecialchars($tour['category_name'] ?? '') ?></div>
                                </div>
                                <div class="position-absolute" style="right:18px; bottom:18px;">
                                    <div class="bg-white p-2 rounded shadow-sm text-dark">
                                        <div class="fw-bold fs-5"><?= $priceShort ?></div>
                                        <div class="small text-muted">Giá khởi điểm</div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($thumbsToShow)): ?>
                                <div class="tour-thumbs d-flex gap-2 p-3 align-items-center" style="overflow:auto">
                                    <?php foreach ($thumbsToShow as $i => $timg):
                                        $turl = (strpos($timg, 'http') === 0) ? $timg : BASE_ASSETS_UPLOADS . $timg;
                                    ?>
                                        <div class="thumb-item me-2" style="width:92px; height:64px; overflow:hidden; border-radius:6px;">
                                            <img src="<?= $turl ?>" alt="thumb-<?= $i ?>" style="width:100%; height:100%; object-fit:cover;" data-index="<?= $i + 1 ?>">
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if ($remaining > 0): ?>
                                        <div class="thumb-item me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width:92px; height:64px; border-radius:6px;">+<?= $remaining ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Giới thiệu</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($tour['description'])): ?>
                                    <div class="text-muted" style="white-space:pre-line"><?= nl2br(htmlspecialchars($tour['description'])) ?></div>
                                <?php else: ?>
                                    <div class="text-muted">Không có mô tả.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Chính sách & Lưu ý</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($tour['policy'])): ?>
                                    <div class="text-muted" style="white-space:pre-line"><?= nl2br(htmlspecialchars($tour['policy'])) ?></div>
                                <?php else: ?>
                                    <div class="text-muted">Không có chính sách.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-white">
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
                </div>
            </div>

            <div class="col-lg-5">
                <div class="position-sticky" style="top:90px;">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="text-muted small">Mã Tour</div>
                                    <div class="fw-bold"><?= htmlspecialchars($tour['id'] ?? '') ?></div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">Đánh giá</div>
                                    <div class="fw-bold"><?= $avgRating ?> / 5</div>
                                    <div class="small text-muted">(<?= $bookingCount ?> lượt đặt)</div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-muted">Giá</div>
                                <div class="fw-bold fs-5"><?= $priceShort ?></div>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-muted">Thời lượng</div>
                                <div class="fw-bold"><?= htmlspecialchars($tour['duration'] ?? ($tour['days'] ?? '')) ?></div>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-muted">Số chỗ</div>
                                <div class="fw-bold"><?= htmlspecialchars($tour['capacity'] ?? $tour['seats'] ?? 'N/A') ?></div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <div class="text-muted">Ngày khởi hành</div>
                                <div class="fw-bold"><?= htmlspecialchars($tour['start_date'] ?? '-') ?></div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL_ADMIN ?>&action=bookings/create&tour_id=<?= urlencode($tour['id'] ?? '') ?>" class="btn btn-primary">Tạo đặt chỗ</a>
                                <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?= urlencode($tour['id'] ?? '') ?>" class="btn btn-outline-secondary">Chỉnh sửa tour</a>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Thông tin nhà cung cấp</h6>
                        </div>
                        <div class="card-body">
                            <div class="small text-muted">Nhà cung cấp</div>
                            <div class="fw-bold mb-2"><?= htmlspecialchars($tour['supplier_name'] ?? '-') ?></div>
                            <div class="small text-muted">Liên hệ</div>
                            <div class="mb-2"><?= nl2br(htmlspecialchars($tour['supplier_contact'] ?? ($tour['supplier_phone'] ?? ''))) ?></div>
                            <div class="small text-muted">Tạo lúc</div>
                            <div class="text-muted"><?= $createdDate ?></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Gói giá</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($pricingOptions)): ?>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($pricingOptions as $p): ?>
                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                            <div>
                                                <div class="fw-600"><?= htmlspecialchars($p['label'] ?? '') ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($p['description'] ?? '') ?></div>
                                            </div>
                                            <div class="fw-bold"><?= number_format($p['price'] ?? 0) ?> đ</div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-muted">Chưa có gói giá.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>