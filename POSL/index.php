<?php
require 'db.php';

$courses = $pdo->query("SELECT * FROM transport_types")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Водить.РФ</title>

    <link href="lib/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="#">
            Водит.РФ
        </a>

        <div>

            <a href="login.php" class="btn btn-outline-light me-2">
                Вход
            </a>

            <a href="register.php" class="btn btn-primary">
                Регистрация
            </a>

        </div>

    </div>

</nav>

<!-- SLIDER -->

<div class="container mt-4">

    <div id="mainSlider" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner rounded shadow">

            <div class="carousel-item active" data-bs-interval="3000">

                <img src="images/67be0e9604c2fe619a95ca87.jpg"
                     class="d-block w-100 slider-img">

            </div>

            <div class="carousel-item" data-bs-interval="3000">

                <img src="images/658aad453358fbce48a7b97f.jpg"
                     class="d-block w-100 slider-img">

            </div>

            <div class="carousel-item" data-bs-interval="3000">

                <img src="images/5946dd94900ba9b56b0d52c88fff9d0d.jpg"
                     class="d-block w-100 slider-img">

            </div>

            <div class="carousel-item" data-bs-interval="3000">

                <img src="images/64536aafe5a415045ada8c4a.jpg"
                     class="d-block w-100 slider-img">

            </div>

        </div>

        <!-- КНОПКИ -->

        <button class="carousel-control-prev"
                type="button"
                data-bs-target="#mainSlider"
                data-bs-slide="prev">

            <span class="carousel-control-prev-icon"></span>

        </button>

        <button class="carousel-control-next"
                type="button"
                data-bs-target="#mainSlider"
                data-bs-slide="next">

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

</div>

<!-- КУРСЫ -->

<div class="container mt-5">

    <h2 class="text-center mb-4">
        Курсы обучения
    </h2>

    <div class="row g-4">

        <?php foreach($courses as $course): ?>

            <div class="col-md-4">

                <div class="card shadow h-100">

                    <img src="<?= $course['image'] ?>"
                         class="card-img-top course-img">

                    <div class="card-body">

                        <h5 class="card-title">
                            <?= $course['title'] ?>
                        </h5>

                        <p class="card-text">
                            <?= $course['description'] ?>
                        </p>

                        <a href="register.php"
                           class="btn btn-primary">

                            Записаться

                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<!-- FOOTER -->

<footer class="bg-dark text-white text-center p-3 mt-5">

    © 2026 Водить.РФ

</footer>

<script src="lib/bootstrap.bundle.min.js"></script>

</body>

</html>