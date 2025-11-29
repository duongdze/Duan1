<?php
class AuthorController
{
    private $user;

    public function __construct()
    {
        $this->user = new UserModel();
    }
    public function login()
    {
        require_once PATH_VIEW_ADMIN . 'auth/login.php';
    }

    public function loginProcess()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Thử đăng nhập bằng phương thức an toàn (với password_verify)
            $user = $this->user->checkLogin($email, $password);

            if ($user) {
                // Đăng nhập thành công với mật khẩu đã được băm
                $_SESSION['user'] = $user;
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_id'] = $user['user_id'];
                // nếu user là HDV, ghép guide_id vào session
                if (!empty($user['role']) && $user['role'] === 'guide') {
                    require_once PATH_MODEL . 'Guide.php';
                    $guideModel = new Guide();
                    $guideRow = $guideModel->find('*', 'user_id = :uid', ['uid' => $user['user_id']]);
                    if ($guideRow) {
                        $_SESSION['guide_id'] = $guideRow['id'];
                    }
                }
                header('Location: ' . BASE_URL_ADMIN); // Chuyển hướng về dashboard
                exit;
            }

            // Nếu đăng nhập an toàn thất bại, kiểm tra trường hợp mật khẩu cũ (văn bản thuần)
            // và tự động cập nhật nếu khớp. Đây là cơ chế "tự sửa lỗi".
            $userData = $this->user->find('*', 'email = :email AND role = :role', [
                'email' => $email,
                'role' => 'admin'
            ]);

            // So sánh mật khẩu văn bản thuần
            if ($userData && $userData['password_hash'] === $password) {
                // Mật khẩu cũ đúng. Mã hóa và cập nhật lại vào DB.
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $this->user->update(['password_hash' => $newHash], 'user_id = :id', ['id' => $userData['user_id']]);

                // Đăng nhập cho người dùng
                $userData['password_hash'] = $newHash; // Cập nhật lại hash trong session
                $_SESSION['user'] = $userData;
                // nếu user là HDV, ghép guide_id vào session
                if (!empty($userData['role']) && $userData['role'] === 'guide') {
                    require_once PATH_MODEL . 'Guide.php';
                    $guideModel = new Guide();
                    $guideRow = $guideModel->find('*', 'user_id = :uid', ['uid' => $userData['user_id']]);
                    if ($guideRow) {
                        $_SESSION['guide_id'] = $guideRow['id'];
                    }
                }
                header('Location: ' . BASE_URL_ADMIN);
                exit;
            }

            // Nếu cả hai cách đều thất bại, báo lỗi.
            $error = "Thông tin đăng nhập không hợp lệ";
            require_once PATH_VIEW_ADMIN . 'auth/login.php';
        }
    }
    public function logout()
    {
        // Hủy session một cách an toàn
        unset($_SESSION['user']);
        unset($_SESSION['guide_id']);
        session_destroy();
        // Chuyển hướng thẳng về trang login
        header('location: ' . BASE_URL_ADMIN . '&action=login');
        exit;
    }

    public function accountInfo()
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            header('Location: ' . BASE_URL_ADMIN . '&action=login');
            exit;
        }

        $title = 'Thông tin tài khoản';
        $view = 'pages/account/index';
        require_once PATH_VIEW_ADMIN_MAIN;
    }
}
