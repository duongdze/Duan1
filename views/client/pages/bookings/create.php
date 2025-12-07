<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<div class="container my-5">
    <div class="row">
        <!-- Booking Form -->
        <div class="col-lg-8">
            <h2 class="mb-4 text-primary"><i class="fas fa-check-circle me-2"></i>Đặt Tour Du Lịch</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mb-4">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>?action=booking-store" method="POST">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        <input type="hidden" name="departure_id" value="<?= $departure['id'] ?>">

                        <h5 class="mb-3 text-secondary">Thông tin liên hệ</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="full_name" required value="<?= $_SESSION['user']['full_name'] ?? '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required value="<?= $_SESSION['user']['email'] ?? '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" required value="<?= $_SESSION['user']['phone'] ?? '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" value="<?= $_SESSION['user']['address'] ?? '' ?>">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3 text-secondary">Số lượng khách</h5>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Người lớn (>12 tuổi)</label>
                                <input type="number" class="form-control" name="adults" id="adults" min="1" value="1" required onchange="calculateTotal()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trẻ em (5-11 tuổi)</label>
                                <input type="number" class="form-control" name="children" id="children" min="0" value="0" onchange="calculateTotal()">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                             <button type="submit" class="btn btn-primary btn-lg w-100">
                                 Xác nhận đặt tour
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="col-lg-4">
            <div class="card shadow border-0 bg-light sticky-top" style="top: 20px">
                <div class="card-body p-4">
                    <h4 class="mb-3 text-primary">Thông tin tour</h4>
                    <img src="<?= BASE_ASSETS_UPLOADS . $this->tourModel->getRelatedData('tour_gallery_images', $tour['id'])[0]['image_url'] ?? 'https://via.placeholder.com/400x200' ?>" class="img-fluid rounded mb-3" alt="Tour Image">
                    <h5 class="fw-bold"><?= htmlspecialchars($tour['name']) ?></h5>
                    <p class="text-muted small mb-2"><i class="fas fa-barcode me-2"></i>Mã tour: <?= $tour['id'] ?></p>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Khởi hành:</span>
                        <span class="fw-bold text-success"><?= date('d/m/Y', strtotime($departure['departure_date'])) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                         <span>Thời gian:</span>
                         <span class="fw-bold"><?= $tour['duration_days'] ?> Ngày</span>
                    </div>
                    
                    <hr>
                    
                    <div class="summary-price">
                        <div class="d-flex justify-content-between mb-1">
                             <span>Người lớn:</span>
                             <span>x <span id="summary-adults">1</span></span>
                             <span class="fw-bold"><?= number_format($departure['price_adult'] ?: $tour['base_price']) ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                             <span>Trẻ em:</span>
                             <span>x <span id="summary-children">0</span></span>
                             <span class="fw-bold"><?= number_format($departure['price_child'] ?: ($departure['price_adult'] ?: $tour['base_price'])) ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                             <h5 class="mb-0">Tổng cộng:</h5>
                             <h5 class="mb-0 text-danger fw-bold" id="total-price">...</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const priceAdult = <?= $departure['price_adult'] ?: $tour['base_price'] ?>;
    const priceChild = <?= $departure['price_child'] ?: ($departure['price_adult'] ?: $tour['base_price']) ?>;

    function calculateTotal() {
        const adults = parseInt(document.getElementById('adults').value) || 0;
        const children = parseInt(document.getElementById('children').value) || 0;
        
        document.getElementById('summary-adults').textContent = adults;
        document.getElementById('summary-children').textContent = children;
        
        const total = (adults * priceAdult) + (children * priceChild);
        document.getElementById('total-price').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
    }
    
    // Init call
    calculateTotal();
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
