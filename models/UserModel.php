<?php
class UserModel extends BaseModel
{
    protected $table = 'users';

    /**
     * Kiểm tra đăng nhập cho admin.
     * @param string $email
     * @param string $password
     * @return array|false
     */

    public function checkLogin($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email AND (role = 'admin' OR role = 'guide') LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // DB column is `password` (may be plain text for old data or a hash)
        $stored = $user['password_hash'] ?? '';

        // Nếu mật khẩu trong DB là hash và khớp
        if (!empty($stored) && password_verify($password, $stored)) {
            return $user;
        }

        // Nếu mật khẩu trong DB lưu plaintext (ví dụ dữ liệu mẫu) -> so sánh trực tiếp
        if ($stored === $password) {
            // Hash lại và cập nhật DB để chuyển sang lưu hash
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            try {
                $this->update(['password' => $newHash], 'id = :id', ['id' => $user['id']]);
                // Cập nhật mảng user để session lưu đúng trạng thái (không bắt buộc)
                $user['password'] = $newHash;
            } catch (Exception $e) {
                // Nếu cập nhật thất bại, vẫn cho phép đăng nhập nhưng ghi log
                error_log('Failed to update user password hash: ' . $e->getMessage());
            }
            return $user;
        }

        // Không khớp
        return false;
    }
}
