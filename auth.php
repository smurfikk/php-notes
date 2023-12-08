<?php
include 'functions.php';

$error_auth = null;
$login = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['loginInput']) && isset($_POST['passwordInput'])) {
        $login = $_POST['loginInput'];
        $password = $_POST['passwordInput'];
        $id = checkCredentials($login, $password);
        if ($id != null) {
            session_start();
            $_SESSION['is_logged'] = true;
            $_SESSION['id'] = $id;
            header("Location: /index.php");
            exit;
        } else {
            $error_auth = "Неверный логин или пароль";
        }
    }
}
?>

<!doctype html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация</title>
    <link rel="stylesheet" href="static/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body class="form-signing">
<form action="auth.php" method="POST">
    <h1 class="mt-5 text-center">Авторизация</h1>
    <?php
    if ($error_auth != null)
        echo "<div class='alert alert-danger alert-dismissible' role='alert'>" . $error_auth . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"
    ?>
    <div class="form-floating mt-3">
        <input type="text" class="form-control" id="loginInput" name="loginInput" placeholder="Логин"
               value="<?php echo $login; ?>" required>
        <label for="loginInput">Логин</label>
    </div>
    <div class="form-floating mt-1">
        <input type="password" class="form-control" id="passwordInput" name="passwordInput"
               placeholder="Пароль" required>
        <label for="passwordInput">Пароль</label>
    </div>
    <div class="mt-2">
        <a class="form-link" href="registration.php">Регистрация</a>
    </div>
    <button class="btn btn-primary w-100 mt-3" type="submit">Войти</button>

</form>
<script src="static/js/fontawesome.js"></script>
<script src="static/js/bootstrap.bundle.min.js"></script>
<script src="static/js/jquery-3.6.0.min.js"></script>
</body>
</html>