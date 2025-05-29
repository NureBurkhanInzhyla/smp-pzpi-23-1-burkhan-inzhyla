<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }

    function loginOrRegister (PDO $pdo, string $username, string $password): bool{
        $stmt = $pdo->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['username'];
                $_SESSION['login_time'] = date("Y-m-d H:i:s");
                $stmt = $pdo->prepare("UPDATE User SET login_time = ? WHERE username = ?");
                $stmt->execute([date("Y-m-d H:i:s"), $user['username']]);

                return true;
            } else {
                return false;
            }
        }
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO User (username, password, login_time) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, date("Y-m-d H:i:s")]);

            $_SESSION['user'] = $username;
            $_SESSION['login_time'] = date("Y-m-d H:i:s");
            return true;
        }
    }

    function getUserProfile(PDO $pdo, string $username): array {
        $stmt = $pdo->prepare("SELECT username, name, surname, birthday, description, profile_image FROM User WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateUserProfile(PDO $pdo, string $username, array $data): bool {
        $stmt = $pdo->prepare("
            UPDATE User SET name = ?, surname = ?, birthday = ?, description = ?, profile_image = ? WHERE username = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['surname'],
            $data['birthday'],
            $data['description'], 
            $data['profile_image'],
            $username
        ]);
           if (!$result) {
        print_r($stmt->errorInfo());
    }
    }

?>
