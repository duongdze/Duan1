<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- Hero Section -->
<div class="tour-hero position-relative">
    <?php 
    $heroImage = !empty($images) ? BASE_ASSETS_UPLOADS . $images[0]['image_url'] : 'https://via.placeholder.com/1920x600';
    // Find main image if set
    foreach($images as $img) {
        if (!empty($img['main_img'])) {
            $heroImage = BASE_ASSETS_UPLOADS . $img['image_url'];
            break;
        }
    }
    ?>
    <img src="<?= $heroImage ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-100 h-100 object-fit-cover">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6));"></div>
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-75 hero-content">
        <span class="badge bg-primary px-3 py-2 mb-3 rounded-pill text-uppercase letter-spacing-1"><?= htmlspecialchars($tour['category_name'] ?? 'General') ?></span>
        <h1 class="display-3 fw-bold text-shadow mb-3"><?= htmlspecialchars($tour['name']) ?></h1>
        <?php if (!empty($tour['subtitle'])): ?>
            <p class="lead text-shadow opacity-90"><?= htmlspecialchars($tour['subtitle']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container my-5" style="margin-top: -60px; position: relative; z-index: 10;">
    <div class="row">
        <!-- Left Content -->
        <div class="col-lg-8">
            <!-- Overview Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="info-card-box text-center h-100 hover-lift">
                        <div class="info-card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h6 class="text-uppercase text-muted small fw-bold">Thời gian</h6>
                        <p class="h5 mb-0 fw-bold"><?= htmlspecialchars($tour['duration_days'] ?? 'N/A') ?> Ngày</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card-box text-center h-100 hover-lift">
                        <div class="info-card-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h6 class="text-uppercase text-muted small fw-bold">Quy mô</h6>
                        <p class="h5 mb-0 fw-bold"><?= htmlspecialchars($tour['max_participants'] ?? 'N/A') ?> chỗ</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card-box text-center h-100 hover-lift">
                        <div class="info-card-icon">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <h6 class="text-uppercase text-muted small fw-bold">Khởi hành</h6>
                        <p class="h5 mb-0 fw-bold">Hàng tuần</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <section class="mb-5">
                <h3 class="mb-4 text-primary">Giới thiệu tour</h3>
                <div class="bg-white p-4 rounded shadow-soft">
                    <div class="tour-description text-justify">
                        <?= nl2br($tour['description']) ?>
                    </div>
                </div>
            </section>

            <!-- Gallery -->
            <?php if (!empty($images)): ?>
            <section class="mb-5">
                <h3 class="mb-4 text-primary">Thư viện ảnh</h3>
                <div class="row g-3">
                    <?php foreach(array_slice($images, 0, 6) as $img): ?>
                        <div class="col-md-4 col-6">
                            <img src="<?= BASE_ASSETS_UPLOADS . $img['image_url'] ?>" class="gallery-img w-100 shadow-sm" alt="Tour Image">
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Itinerary Timeline -->
            <?php if (!empty($itinerarySchedule)): ?>
            <section class="mb-5">
                <h3 class="mb-4 text-primary">Lịch trình chi tiết</h3>
                <div class="timeline">
                    <?php foreach($itinerarySchedule as $index => $item): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <?= $item['day_number'] ?>
                            </div>
                            <div class="timeline-content shadow-soft hover-lift">
                                <h5 class="fw-bold mb-3 text-primary">
                                    <?= htmlspecialchars($item['title'] ?? $item['day_label']) ?>
                                </h5>
                                <div class="text-muted">
                                    <?= nl2br($item['description'] ?? $item['activities'] ?? '') ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Policies -->
            <?php if (!empty($policies)): ?>
            <section class="mb-5">
                <h3 class="mb-4 text-primary">Chính sách & Điều khoản</h3>
                <div class="row g-4">
                    <?php foreach($policies as $policy): ?>
                        <div class="col-12">
                            <div class="policy-card p-4 rounded h-100">
                                <h5 class="card-title text-secondary mb-3">
                                    <i class="fas fa-shield-alt me-2"></i><?= htmlspecialchars($policy['name']) ?>
                                </h5>
                                <div class="card-text text-muted">
                                    <?= nl2br($policy['description']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>

        <!-- Right Stick Sidebar -->
        <div class="col-lg-4">
            <div class="booking-card card shadow-lg sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-header text-center">
                    <p class="text-muted mb-1 text-uppercase small ls-1">Giá trọn gói chỉ từ</p>
                    <div class="booking-price">
                        <?= number_format($tour['base_price'], 0, ',', '.') ?> <span class="fs-5 text-dark">đ</span>
                    </div>
                </div>
                <div class="card-body p-4 booking-form">
                    <?php if (!empty($departures)): ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2"><i class="far fa-calendar-alt me-2"></i>Chọn ngày khởi hành</label>
                            <select class="form-select form-select-lg" id="departureSelect">
                                <option value="">-- Chọn ngày --</option>
                                <?php foreach($departures as $dep): ?>
                                    <option value="<?= $dep['id'] ?>" data-price="<?= $dep['price_adult'] > 0 ? $dep['price_adult'] : $tour['base_price'] ?>">
                                        <?= date('d/m/Y', strtotime($dep['departure_date'])) ?> 
                                        (Còn <?= $dep['max_seats'] - ($dep['booked_seats']??0) ?> chỗ)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning border-0 bg-warning-subtle mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>Hiện chưa có lịch khởi hành.
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-3">
                        <button class="btn btn-book-now text-white btn-lg shadow-sm" type="button" onclick="bookNow()">
                            <i class="fas fa-paper-plane me-2"></i> Đặt Tour Ngay
                        </button>
                        <button class="btn btn-outline-primary btn-lg" type="button">
                            <i class="fas fa-headset me-2"></i> Tư vấn miễn phí
                        </button>
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="d-flex align-items-center bg-light p-3 rounded">
                        <div class="flex-shrink-0">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-primary">
                                <i class="fas fa-building fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0 text-muted small text-uppercase fw-bold">Đơn vị tổ chức</p>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($tour['supplier_name'] ?? 'VietTravel') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function bookNow() {
        try {
            console.log('Book Now clicked');
            const departureSelect = document.getElementById('departureSelect');
            
            if (!departureSelect) {
                console.error('Departure select not found');
                alert('Lỗi hệ thống: Không tìm thấy ô chọn ngày!');
                return;
            }

            console.log('Selected value:', departureSelect.value);

            if (!departureSelect.value) {
                alert('Vui lòng chọn ngày khởi hành để tiếp tục!');
                departureSelect.focus();
                return;
            }
            
            const tourId = <?= $tour['id'] ?>;
            const departureId = departureSelect.value;
            const url = `<?= BASE_URL ?>?action=booking-create&tour_id=${tourId}&departure_id=${departureId}`;
            
            console.log('Redirecting to:', url);
            window.location.href = url;
        } catch (e) {
            console.error('Booking error:', e);
            alert('Có lỗi xảy ra: ' + e.message);
        }
    }
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
