<?php
require 'db.php';
$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE login = ?
    ");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    if($user){
        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user;
            if($user['role'] == 'admin'){
                header("Location: admin.php");
                exit();
            }
            else{
                header("Location: profile.php");
                exit();
            }
        }
        else{
            $message = "Неверный пароль";
        }
    }
    else{
        $message = "Пользователь не найден";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link href="lib/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">
                    Авторизация
                </h2>
                <?php if($message): ?>
                    <div class="alert alert-danger">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="text"
                           name="login"
                           class="form-control mb-3"
                           placeholder="Логин"
                           required>
                    <input type="password"
                           name="password"
                           class="form-control mb-3"
                           placeholder="Пароль"
                           required>
                    <button class="btn btn-dark w-100">
                        Войти
                    </button>
                </form>
                <div class="text-center mt-3">
                    <a href="register.php">
                        Еще не зарегистрированы?
                        Регистрация
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="lib/bootstrap.bundle.min.js"></script>
</body>
</html>
