
<?php
require 'db.php';
if(
    !isset($_SESSION['user'])
    ||
    $_SESSION['user']['role'] != 'admin'
){
    header("Location: login.php");
    exit();
}
/* ИЗМЕНЕНИЕ СТАТУСА */
if(isset($_POST['application_id'])){
    $application_id = $_POST['application_id'];
    $status_id = $_POST['status_id'];
    $update = $pdo->prepare("
        UPDATE applications
        SET status_id = ?
        WHERE id = ?
    ");
    $update->execute([
        $status_id,
        $application_id
    ]);
}
/* ФИЛЬТР */
$where = "";
$params = [];
if(isset($_GET['status']) && $_GET['status'] != ""){
    $where = "WHERE applications.status_id = ?";
    $params[] = $_GET['status'];
}
/* ЗАЯВКИ */
$sql = "
SELECT
    applications.id,
    users.fullname,
    users.phone,
    transport_types.title AS transport,
    payment_methods.title AS payment,
    application_statuses.title AS status,
    applications.start_date,
    applications.created_at
FROM applications
JOIN users
ON applications.user_id = users.id
JOIN transport_types
ON applications.transport_type_id = transport_types.id
JOIN payment_methods
ON applications.payment_method_id = payment_methods.id
JOIN application_statuses
ON applications.status_id = application_statuses.id
$where
ORDER BY applications.id DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$applications = $stmt->fetchAll();
/* СТАТУСЫ */
$statuses = $pdo->query("
    SELECT *
    FROM application_statuses
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link href="lib/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand"
           href="index.php">
            Админ панель
        </a>
        <div>
            <a href="logout.php"
               class="btn btn-danger">
                Выйти
            </a>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <!-- FILTER -->
    <div class="card shadow p-4 mb-4">
        <h4 class="mb-3">
            Фильтр заявок
        </h4>
        <form method="GET">
            <div class="row">
                <div class="col-md-4">
                    <select name="status"
                            class="form-select">
                        <option value="">
                            Все статусы
                        </option>
                        <?php foreach($statuses as $status): ?>
                            <option
                                value="<?= $status['id'] ?>">
                                <?= $status['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Применить
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- APPLICATIONS -->
    <div class="card shadow p-4">
        <h3 class="mb-4">
            Все заявки
        </h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Телефон</th>
                    <th>Транспорт</th>
                    <th>Дата начала</th>
                    <th>Оплата</th>
                    <th>Статус</th>
                    <th>Изменить</th>
                </tr>
                <?php foreach($applications as $app): ?>
                    <tr>
                        <td>
                            <?= $app['id'] ?>
                        </td>
                        <td>
                            <?= $app['fullname'] ?>
                        </td>
                        <td>
                            <?= $app['phone'] ?>
                        </td>
                        <td>
                            <?= $app['transport'] ?>
                        </td>
                        <td>
                            <?= $app['start_date'] ?>
                        </td>
                        <td>
                            <?= $app['payment'] ?>
                        </td>
                        <td>
                            <?= $app['status'] ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden"
                                       name="application_id"
                                       value="<?= $app['id'] ?>">
                                <select name="status_id"
                                        class="form-select mb-2">
                                    <?php foreach($statuses as $status): ?>
                                        <option
                                            value="<?= $status['id'] ?>">
                                            <?= $status['title'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-success w-100">
                                    Сохранить
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<script src="lib/bootstrap.bundle.min.js"></script>
</body>
</html>