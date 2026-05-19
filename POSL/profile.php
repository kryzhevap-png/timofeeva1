<?php
require 'db.php';

if(!isset($_SESSION['user'])){

    header("Location: login.php");
    exit();

}

$user = $_SESSION['user'];

$sql = "

SELECT

    applications.id,

    transport_types.title AS transport,

    payment_methods.title AS payment,

    application_statuses.title AS status,

    applications.start_date,

    applications.created_at

FROM applications

JOIN transport_types
ON applications.transport_type_id = transport_types.id

JOIN payment_methods
ON applications.payment_method_id = payment_methods.id

JOIN application_statuses
ON applications.status_id = application_statuses.id

WHERE applications.user_id = ?

ORDER BY applications.id DESC

";

$stmt = $pdo->prepare($sql);

$stmt->execute([$user['id']]);

$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Личный кабинет</title>

    <link href="lib/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand"
           href="index.php">

            Водить.РФ

        </a>

        <div>

            <a href="create_application.php"
               class="btn btn-primary me-2">

                Создать заявку

            </a>

            <a href="logout.php"
               class="btn btn-danger">

                Выйти

            </a>

        </div>

    </div>

</nav>

<!-- PROFILE -->

<div class="container mt-5">

    <!-- USER INFO -->

    <div class="card shadow p-4 mb-5">

        <h2 class="mb-4">

            Добро пожаловать,
            <?= $user['fullname'] ?>

        </h2>

        <div class="row">

            <div class="col-md-6">

                <p>

                    <strong>Email:</strong>

                    <?= $user['email'] ?>

                </p>

            </div>

            <div class="col-md-6">

                <p>

                    <strong>Телефон:</strong>

                    <?= $user['phone'] ?>

                </p>

            </div>

        </div>

    </div>

    <!-- SLIDER -->

    <div id="profileSlider"
         class="carousel slide mb-5"
         data-bs-ride="carousel">

        <div class="carousel-inner rounded shadow">

            <div class="carousel-item active"
                 data-bs-interval="3000">

                <img src="images/64536aafe5a415045ada8c4a.jpg"
                     class="d-block w-100 slider-img">

            </div>

            <div class="carousel-item"
                 data-bs-interval="3000">

                <img src="images/64536aafe5a415045ada8c4a.jpg"
                     class="d-block w-100 slider-img">

            </div>

            <div class="carousel-item"
                 data-bs-interval="3000">

                <img src="images/64536aafe5a415045ada8c4a.jpg"
                     class="d-block w-100 slider-img">

            </div>

            <div class="carousel-item"
                 data-bs-interval="3000">

                <img src="images/64536aafe5a415045ada8c4a.jpg"
                     class="d-block w-100 slider-img">

            </div>

        </div>

        <!-- BUTTONS -->

        <button class="carousel-control-prev"
                type="button"
                data-bs-target="#profileSlider"
                data-bs-slide="prev">

            <span class="carousel-control-prev-icon"></span>

        </button>

        <button class="carousel-control-next"
                type="button"
                data-bs-target="#profileSlider"
                data-bs-slide="next">

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

    <!-- APPLICATIONS -->

    <div class="card shadow p-4">

        <h3 class="mb-4">

            Мои заявки

        </h3>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">

                <tr>

                    <th>ID</th>

                    <th>Транспорт</th>

                    <th>Дата начала</th>

                    <th>Оплата</th>

                    <th>Статус</th>

                    <th>Дата создания</th>

                    <th>Отзыв</th>

                </tr>

                <?php foreach($applications as $app): ?>

                    <tr>

                        <td>

                            <?= $app['id'] ?>

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

                            <?= $app['created_at'] ?>

                        </td>

                        <td>

                            <?php if($app['status'] == 'Обучение завершено'): ?>

                                <a href="review.php?id=<?= $app['id'] ?>"
                                   class="btn btn-success btn-sm">

                                    Оставить отзыв

                                </a>

                            <?php else: ?>

                                <span class="text-muted">

                                    Недоступно

                                </span>

                            <?php endif; ?>

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