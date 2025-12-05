<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="dashboard">
    <div class="dashboard-container">
        <!-- Modern Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="breadcrumb-modern">
                        <a href="<?= BASE_URL_ADMIN ?>&action=/" class="breadcrumb-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current">Báo Cáo</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-chart-line title-icon"></i>
                            Hệ Thống Báo Cáo
                        </h1>
                        <p class="page-subtitle">Tổng hợp các báo cáo và phân tích dữ liệu kinh doanh</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Reports Grid -->
        <section class="reports-grid-section">
            <div class="reports-grid">
                <!-- Dashboard Report Card -->
                <a href="<?= BASE_URL_ADMIN . '&action=reports/dashboard' ?>" class="report-card report-card-primary">
                    <div class="report-card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="report-card-content">
                        <h3 class="report-card-title">Báo Cáo Tổng Quan</h3>
                        <p class="report-card-description">
                            Dashboard tổng hợp với KPI, biểu đồ xu hướng và thống kê toàn diện
                        </p>
                        <div class="report-card-features">
                            <span class="feature-badge"><i class="fas fa-chart-line"></i> Xu hướng</span>
                            <span class="feature-badge"><i class="fas fa-tachometer-alt"></i> KPI</span>
                            <span class="feature-badge"><i class="fas fa-bell"></i> Cảnh báo</span>
                        </div>
                    </div>
                    <div class="report-card-footer">
                        <span class="report-link">Xem báo cáo <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>

                <!-- Financial Report Card -->
                <a href="<?= BASE_URL_ADMIN . '&action=reports/financial' ?>" class="report-card report-card-success">
                    <div class="report-card-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="report-card-content">
                        <h3 class="report-card-title">Báo Cáo Tài Chính</h3>
                        <p class="report-card-description">
                            Doanh thu, chi phí, lợi nhuận chi tiết theo từng tour
                        </p>
                        <div class="report-card-features">
                            <span class="feature-badge"><i class="fas fa-money-bill-wave"></i> Doanh thu</span>
                            <span class="feature-badge"><i class="fas fa-receipt"></i> Chi phí</span>
                            <span class="feature-badge"><i class="fas fa-chart-bar"></i> Lợi nhuận</span>
                        </div>
                    </div>
                    <div class="report-card-footer">
                        <span class="report-link">Xem báo cáo <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>

                <!-- Booking Report Card -->
                <a href="<?= BASE_URL_ADMIN . '&action=reports/bookings' ?>" class="report-card report-card-info">
                    <div class="report-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="report-card-content">
                        <h3 class="report-card-title">Báo Cáo Booking</h3>
                        <p class="report-card-description">
                            Thống kê booking, tỷ lệ thành công và phân tích nguồn khách
                        </p>
                        <div class="report-card-features">
                            <span class="feature-badge"><i class="fas fa-users"></i> Khách hàng</span>
                            <span class="feature-badge"><i class="fas fa-percent"></i> Tỷ lệ</span>
                            <span class="feature-badge"><i class="fas fa-stream"></i> Nguồn</span>
                        </div>
                    </div>
                    <div class="report-card-footer">
                        <span class="report-link">Xem báo cáo <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>

                <!-- Conversion Report Card -->
                <a href="<?= BASE_URL_ADMIN . '&action=reports/conversion' ?>" class="report-card report-card-warning">
                    <div class="report-card-icon">
                        <i class="fas fa-funnel-dollar"></i>
                    </div>
                    <div class="report-card-content">
                        <h3 class="report-card-title">Tỷ Lệ Chuyển Đổi</h3>
                        <p class="report-card-description">
                            Phân tích funnel từ inquiry đến thanh toán, conversion rate
                        </p>
                        <div class="report-card-features">
                            <span class="feature-badge"><i class="fas fa-filter"></i> Funnel</span>
                            <span class="feature-badge"><i class="fas fa-percentage"></i> Rate</span>
                            <span class="feature-badge"><i class="fas fa-clock"></i> Thời gian</span>
                        </div>
                    </div>
                    <div class="report-card-footer">
                        <span class="report-link">Xem báo cáo <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>

                <!-- Feedback Report Card -->
                <a href="<?= BASE_URL_ADMIN . '&action=reports/feedback' ?>" class="report-card report-card-purple">
                    <div class="report-card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="report-card-content">
                        <h3 class="report-card-title">Phản Hồi & Đánh Giá</h3>
                        <p class="report-card-description">
                            Đánh giá khách hàng, rating distribution, sentiment analysis
                        </p>
                        <div class="report-card-features">
                            <span class="feature-badge"><i class="fas fa-star-half-alt"></i> Rating</span>
                            <span class="feature-badge"><i class="fas fa-smile"></i> Sentiment</span>
                            <span class="feature-badge"><i class="fas fa-comment-dots"></i> Feedback</span>
                        </div>
                    </div>
                    <div class="report-card-footer">
                        <span class="report-link">Xem báo cáo <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>

                <!-- Custom Reports Card (Coming Soon) -->
                <div class="report-card report-card-secondary report-card-disabled">
                    <div class="report-card-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="report-card-content">
                        <h3 class="report-card-title">Báo Cáo Tùy Chỉnh</h3>
                        <p class="report-card-description">
                            Tạo và lưu các báo cáo tùy chỉnh theo nhu cầu riêng
                        </p>
                        <div class="report-card-features">
                            <span class="feature-badge badge-muted">Sắp ra mắt</span>
                        </div>
                    </div>
                    <div class="report-card-footer">
                        <span class="report-link text-muted">Coming soon...</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Stats Section -->
        <section class="quick-stats-section mt-4">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i> Thống Kê Nhanh
            </h2>
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">5</div>
                        <div class="stat-label">Loại Báo Cáo</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">Excel/PDF</div>
                        <div class="stat-label">Export Formats</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">Real-time</div>
                        <div class="stat-label">Dữ Liệu</div>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">Chart.js</div>
                        <div class="stat-label">Biểu Đồ</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<style>
.reports-grid-section {
    margin-top: 2rem;
}

.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.report-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.report-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.report-card:hover::before {
    opacity: 1;
}

.report-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border-color: var(--card-border-color);
}

.report-card-primary {
    --card-color-1: #667eea;
    --card-color-2: #764ba2;
    --card-border-color: #667eea;
}

.report-card-success {
    --card-color-1: #10b981;
    --card-color-2: #059669;
    --card-border-color: #10b981;
}

.report-card-info {
    --card-color-1: #3b82f6;
    --card-color-2: #2563eb;
    --card-border-color: #3b82f6;
}

.report-card-warning {
    --card-color-1: #f59e0b;
    --card-color-2: #d97706;
    --card-border-color: #f59e0b;
}

.report-card-purple {
    --card-color-1: #8b5cf6;
    --card-color-2: #7c3aed;
    --card-border-color: #8b5cf6;
}

.report-card-secondary {
    --card-color-1: #6b7280;
    --card-color-2: #4b5563;
    --card-border-color: #6b7280;
}

.report-card-disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

.report-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.report-card-icon i {
    font-size: 1.75rem;
    color: white;
}

.report-card-content {
    flex: 1;
}

.report-card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #111827;
}

.report-card-description {
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.report-card-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.feature-badge {
    background: #f3f4f6;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    color: #4b5563;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.feature-badge i {
    font-size: 0.7rem;
}

.feature-badge.badge-muted {
    background: #e5e7eb;
    color: #9ca3af;
}

.report-card-footer {
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
    margin-top: auto;
}

.report-link {
    color: var(--card-color-1);
    font-weight: 500;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.report-link i {
    transition: transform 0.3s ease;
}

.report-card:hover .report-link i {
    transform: translateX(4px);
}

.quick-stats-section {
    margin-top: 3rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: #f59e0b;
}

@media (max-width: 768px) {
    .reports-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
