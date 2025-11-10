<?php
class UserModel extends BaseModel{
    protected $table = 'users';

    public function checkLogin($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email AND role = 'admin' LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // So sánh mật khẩu đã hash với mật khẩu người dùng nhập
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
}