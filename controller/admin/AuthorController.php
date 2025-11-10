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
        session_destroy();
        // Chuyển hướng thẳng về trang login
        header('location: ' . BASE_URL_ADMIN . '&action=login');
        exit;
    }

    public function logout()
    {
        // Destroy session and redirect to login
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        header('Location: ?action=login');
        exit;
    }
}
