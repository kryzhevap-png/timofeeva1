<?php
require 'db.php';
$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fullname = trim($_POST['fullname']);
    $birth_date = $_POST['birth_date'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    if(strlen($login) < 6){
        $message = "Логин должен содержать минимум 6 символов";
    }
    elseif(!preg_match("/^[a-zA-Z0-9]+$/", $login)){
        $message = "Логин должен содержать только латинские буквы и цифры";
    }
    elseif(strlen($password) < 8){
        $message = "Пароль должен содержать минимум 8 символов";
    }
    else{
        $check = $pdo->prepare("
            SELECT id
            FROM users
            WHERE login = ?
        ");
        $check->execute([$login]);
        if($check->rowCount() > 0){
            $message = "Такой логин уже существует";
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "
                INSERT INTO users
                (
                    fullname,
                    birth_date,
                    phone,
                    email,
                    login,
                    password
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?
                )
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $fullname,
                $birth_date,
                $phone,
                $email,
                $login,
                $hash
            ]);
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link href="lib/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">
                    Регистрация
                </h2>
                <?php if($message): ?>
                    <div class="alert alert-danger">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="text"
                           name="fullname"
                           class="form-control mb-3"
                           placeholder="ФИО"
                           required>
                    <input type="date"
                           name="birth_date"
                           class="form-control mb-3"
                           required>
                    <input type="text"
                           name="phone"
                           class="form-control mb-3"
                           placeholder="Телефон"
                           required>
                    <input type="email"
                           name="email"
                           class="form-control mb-3"
                           placeholder="Email"
                           required>
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
                    <button class="btn btn-primary w-100">
                        Зарегистрироваться
                    </button>
                </form>
                <div class="text-center mt-3">
                    <a href="login.php">
                        Уже есть аккаунт? Войти
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="lib/bootstrap.bundle.min.js"></script>
</body>
</html>