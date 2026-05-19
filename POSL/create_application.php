<?php
require 'db.php';
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
$transports = $pdo->query("
    SELECT *
    FROM transport_types
")->fetchAll();
$payments = $pdo->query("
    SELECT *
    FROM payment_methods
")->fetchAll();
$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $transport_type_id = $_POST['transport_type_id'];
    $payment_method_id = $_POST['payment_method_id'];
    $start_date = $_POST['start_date'];
    $status_id = 1;
    $sql = "
        INSERT INTO applications
        (
            user_id,
            transport_type_id,
            payment_method_id,
            status_id,
            start_date
        )
        VALUES
        (
            ?, ?, ?, ?, ?
        )
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_SESSION['user']['id'],
        $transport_type_id,
        $payment_method_id,
        $status_id,
        $start_date
    ]);
    $message = "Заявка успешно создана";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Создание заявки</title>
    <link href="lib/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand"
           href="index.php">
            Водить.РФ
        </a>
        <div>
            <a href="profile.php"
               class="btn btn-outline-light me-2">
                Личный кабинет
            </a>
            <a href="logout.php"
               class="btn btn-danger">
                Выйти
            </a>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">
                    Оформление заявки
                </h2>
                <?php if($message): ?>
                    <div class="alert alert-success">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <!-- ТРАНСПОРТ -->
                    <label class="mb-2">
                        Вид транспорта
                    </label>
                    <select name="transport_type_id"
                            class="form-select mb-3"
                            required>
                        <?php foreach($transports as $transport): ?>
                            <option value="<?= $transport['id'] ?>">
                                <?= $transport['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- ДАТА -->
                    <label class="mb-2">
                        Дата начала обучения
                    </label>
                    <input type="date"
                           name="start_date"
                           class="form-control mb-3"
                           required>
                    <!-- ОПЛАТА -->
                    <label class="mb-2">
                        Способ оплаты
                    </label>
                    <select name="payment_method_id"
                            class="form-select mb-4"
                            required>
                        <?php foreach($payments as $payment): ?>
                            <option value="<?= $payment['id'] ?>">
                                <?= $payment['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary w-100">
                        Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="lib/bootstrap.bundle.min.js"></script>
</body>
</html>