<?php
require 'db.php';
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
if(!isset($_GET['id'])){
    header("Location: profile.php");
    exit();
}
$application_id = $_GET['id'];
$user_id = $_SESSION['user']['id'];
/* ПРОВЕРКА ЗАЯВКИ */
$sql = "
SELECT
    applications.id,
    application_statuses.title AS status
FROM applications
JOIN application_statuses
ON applications.status_id = application_statuses.id
WHERE applications.id = ?
AND applications.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $application_id,
    $user_id
]);
$application = $stmt->fetch();
if(!$application){
    header("Location: profile.php");
    exit();
}
if($application['status'] != 'Обучение завершено'){
    die("Отзыв можно оставить только после завершения обучения");
}
$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $review_text = trim($_POST['review_text']);
    $check = $pdo->prepare("
        SELECT id
        FROM reviews
        WHERE application_id = ?
    ");
    $check->execute([$application_id]);
    if($check->rowCount() > 0){
        $message = "Отзыв уже оставлен";
    }
    else{
        $insert = $pdo->prepare("
            INSERT INTO reviews
            (
                user_id,
                application_id,
                review_text
            )
            VALUES
            (
                ?, ?, ?
            )
        ");
        $insert->execute([
            $user_id,
            $application_id,
            $review_text
        ]);
        $message = "Отзыв успешно добавлен";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">
<title>Отзыв</title>
<link href="lib/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand"
   href="profile.php">
    Личный кабинет
</a>
</div>
</nav>
<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card shadow p-4">
<h2 class="text-center mb-4">
    Оставить отзыв
</h2>
<?php if($message): ?>
<div class="alert alert-info">
    <?= $message ?>
</div>
<?php endif; ?>
<form method="POST">
<textarea
    name="review_text"
    class="form-control mb-4"
    rows="5"
    placeholder="Введите ваш отзыв..."
    required></textarea>
<button class="btn btn-primary w-100">
    Отправить отзыв
</button>
</form>
</div>
</div>
</div>
</div>
<script src="lib/bootstrap.bundle.min.js"></script>
</body>
</html>