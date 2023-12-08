<?php
include 'functions.php';

$check_auth = null;
$login = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['loginInput']) && isset($_POST['passwordInput'])) {
        $login = $_POST['loginInput'];
        $password = $_POST['passwordInput'];
        $id = register($login, $password);
        if ($id != null) {
            session_start();
            $_SESSION['is_logged'] = true;
            $_SESSION['id'] = $id;
            header("Location: /index.php");
            exit;
        } else {
            $check_auth = "Пользователь с таким логином уже существует";
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
    <title>Регистрация</title>
    <link rel="stylesheet" href="static/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body class="form-signing">
<form action="registration.php" method="POST"
      oninput="passwordInput2.setCustomValidity(passwordInput.value !== passwordInput2.value ? 'Пароли не совпадают.' : '')">
    <h1 class="mt-5 text-center">Регистрация</h1>
    <?php
    if ($check_auth != null)
        echo "<div class='alert alert-danger alert-dismissible' role='alert'>" . $check_auth . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"
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
    <div class="form-floating mt-1">
        <input type="password" class="form-control" id="passwordInput2" name="passwordInput2"
               placeholder="Подтверждение пароля" required>
        <label for="passwordInput2">Подтверждение пароля</label>
    </div>
    <div class="mt-2">
        <a class="form-link" href="auth.php">Авторизация</a>
    </div>
    <button class="btn btn-primary w-100 mt-3" type="submit">Войти</button>

</form>
<script src="static/js/fontawesome.js"></script>
<script src="static/js/bootstrap.bundle.min.js"></script>
<script src="static/js/jquery-3.6.0.min.js"></script>
</body>
</html>