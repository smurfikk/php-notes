<?php
session_start();
$_SESSION['is_logged'] = false;
$_SESSION['id'] = null;
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Выход</title>
    <link rel="stylesheet" href="static/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
<div class="container mt-5">
    <p>Спасибо, что провели сегодня немного времени с пользой для нашего веб-сайта.</p>

    <a class="form-link" href="auth.php">Войти снова</a>
</div>
<script src="static/js/fontawesome.js"></script>
<script src="static/js/bootstrap.bundle.min.js"></script>
<script src="static/js/jquery-3.6.0.min.js"></script>
<script src="static/js/script.js"></script>
</body>
</html>