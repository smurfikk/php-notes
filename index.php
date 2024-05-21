<!doctype html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Заметки</title>
    <link rel="stylesheet" href="static/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
<?php
include 'functions.php';

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['is_logged'])) {
    $userId = $_SESSION['id'];
    $is_logged = $_SESSION['is_logged'];
    if (!$is_logged) {
        header("Location: /auth.php");
        exit();
    }
    $userLogin = getUser($userId);
    if ($userLogin == null) {
        header("Location: /auth.php");
        exit();
    }
} else {
    header("Location: /auth.php");
    exit();
}


$id = $_GET['id'] ?? null;
?>
<header class="editor">
    <div class="row">
        <div class="col-3 p-2">
            <a href="index.php" class="btn-action" data-bs-toggle="tooltip" data-bs-title="Создать заметку"
               data-bs-placement="bottom">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
            <i id="hideBtn" class="menu-vision btn-action" href="#" data-bs-toggle="tooltip"
               data-bs-title="Скрытие меню"
               data-bs-placement="bottom">
                <i class="fa-solid fa-bars"></i>
            </i>
        </div>
        <div class="col-1 p-2">
            <div class="vr"></div>
        </div>
        <div class="col-1 p-2">
            <i id="btnRemove" class="btn-action" data-bs-toggle="tooltip" data-bs-title="Удалить"
               data-bs-placement="bottom">
                <i class="fa-solid fa-trash"></i>
            </i>
            <i id="btnShare" class="btn-action" data-bs-toggle="modal" data-bs-target="#modalShareLink">
                <i class="fa-solid fa-paperclip" data-bs-toggle="tooltip" data-bs-title="Поделиться"
                   data-bs-placement="bottom"></i>
            </i>
        </div>
        <div class="col-2 p-2">
            <i id="btnBold" class="btn-action" data-bs-toggle="tooltip" data-bs-title="Жирный"
               data-bs-placement="bottom">
                <i class="fa-solid fa-bold"></i>
            </i>
            <i id="btnItalic" class="btn-action" data-bs-toggle="tooltip" data-bs-title="Курсив"
               data-bs-placement="bottom">
                <i class="fa-solid fa-italic"></i>
            </i>
            <i id="btnUnderline" class="btn-action" data-bs-toggle="tooltip" data-bs-title="Подчеркнутый"
               data-bs-placement="bottom">
                <i class="fa-solid fa-underline"></i>
            </i>
        </div>
        <div class="col-3 p-2">
        </div>
        <div class="col-1">
            <p class="userLogin m-auto">
                <?php echo $userLogin ?>
                <a href="logout.php" class="btn btn-action logoutBtn">Выход</a>
            </p>
        </div>
    </div>
</header>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="sidebar col-3">
            <ul class="list-group">
                <?php
                $notesArray = getArrayNotes($userId);
                if (!empty($notesArray)) {
                    foreach ($notesArray as $note) {
                        if ($id == strval($note["id"])) {
                            echo "<li class='note-name list-group-item border-light'>" . $note['name'] . "</li>";
                        } else
                            echo "<a href='index.php?id=" . $note['id'] . "'><li class='note-name list-group-item'>" . $note['name'] . "</li></a>";
                    }
                } else {
                    echo "<li class='note-name list-group-item'><a href='#'>Новая заметка</a></li>";
                }
                ?>
            </ul>
        </div>

        <div class="content col-9">
            <?php
            $note = getNote($id);
            if ($note != null) {
                echo "<input type='number' id='noteId' name='noteId' value='" . $note["id"] . "' hidden>";
                echo "<input type='number' id='userId' name='userId' value='" . $userId . "' hidden>";
                echo "<input type='text' id='hash' name='hash' value='" . $note["hash"] . "' hidden>";
                echo "<input type='text' id='noteTitle' placeholder='Введите название' name='noteTitle' value='" . $note["title"] . "'><br>";
                echo '<br>';
                echo "<textarea class='form-control card-text' id='noteText' style='height: 85vh' name='noteText'>" . $note["content"] . "</textarea>";
            } else {
                echo "<input type='number' id='noteId' name='noteId' value='' hidden>";
                echo "<input type='number' id='userId' name='userId' value='" . $userId . "' hidden>";
                echo "<input type='text' id='hash' name='hash' value='' hidden>";
                echo "<input type='text' placeholder='Введите название' id='noteTitle' name='noteTitle'><br>";
                echo '<br>';
                echo "<textarea class='form-control card-text' id='noteText' style='height: 85vh' name='noteText'></textarea>";
            }
            ?>
            <label for="noteText"></label>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveCopyLink" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Ссылка успешно скопирована
                </div>
                <button type="button" class="btn me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Закрыть">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-sheet" id="modalShareLink" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Настройки доступа</h1>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <label for="ShareLinkNote"></label>
                        <input type="text" class="form-control" id="ShareLinkNote" value="" readonly>
                        <button id="btnCopyLink" type="button" class="btn btn-outline-light">Копировать</button>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="btnSwitchShareAccess"
                            <?php
                            if ($note)
                                if ($note['visibility'])
                                    echo "checked";
                            ?>
                        >
                        <label class="form-check-label" for="btnSwitchShareAccess">Доступ по ссылке</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Готово</button>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="d-flex justify-content-center align-items-center m-0">
    <i class="fa-brands fa-github"></i>
    <a href="https://github.com/smurfikk/php-notes.git" target="_blank">GitHub</a>
</footer>
<script src="static/js/fontawesome.js"></script>
<script src="static/js/bootstrap.bundle.min.js"></script>
<script src="static/js/jquery-3.6.0.min.js"></script>
<script src="static/js/script.js"></script>
</body>
</html>
