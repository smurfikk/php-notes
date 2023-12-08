<?php
include "functions.php";
$id = $_GET['id'] ?? null;
$note = getNote($id);
if ($note == null) {
    header("Location: /index.php");
    exit();
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Note <?php echo $note["id"] ?></title>
    <link rel="stylesheet" href="static/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
<div class="container mt-1">
    <textarea class="form-control card-text" id="noteText" style="height: 90vh" name="noteText" disabled><?php
        echo $note["content"];
        ?></textarea>
    <label for="noteText"></label>
</div>
<footer class="d-flex justify-content-center align-items-center m-0">
    <i class="fa-brands fa-github"></i>
    <a href="https://github.com/smurfikk/php-notes.git" target="_blank">GitHub</a>
</footer>
<script src="static/js/fontawesome.js"></script>
<script src="static/js/bootstrap.bundle.min.js"></script>
<script src="static/js/jquery-3.6.0.min.js"></script>
</body>
</html>