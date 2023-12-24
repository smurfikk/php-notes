<?php
include 'functions.php';

$method = $_POST['method'];
if ($method == "updateNote")
    updateNote();
elseif ($method == "removeNote")
    removeNote();
elseif ($method == "changeVisibilityNote")
    changeVisibilityNote();

function removeNote(): void
{
    $response = array();
    $response['error'] = null;
    $id = $_POST['id'];
    if ($id == "")
        $id = null;

    if (is_numeric($id)) {
        $conn = connect();
        $id = mysqli_real_escape_string($conn, $id);
        if (!$conn->query("DELETE FROM notes WHERE id = $id")) {
            $response['error'] = "Ошибка: " . $conn->error;
        }
        $conn->close();
    } else
        $response['error'] = "Ошибка: неверный id";
    echo json_encode($response);
}

function updateNote(): void
{
    $id = $_POST['id'];
    if ($id == "")
        $id = null;
    $content = $_POST['content'];
    $userId = $_POST['userId'];


    $response = array();
    $response['id'] = $id;
    $response['error'] = null;
    $currentTime = time();

    if (!is_numeric($userId)) {
        $response["error"] = "Неверный id пользователя";
        echo json_encode($response);
        return;
    }

    $conn = connect();

    if (is_numeric($id))
        $id = mysqli_real_escape_string($conn, $id);
    if ($content != "")
        $content = mysqli_real_escape_string($conn, $content);

    if ($id != null && checkIfExists($conn, $id)) {
        if (!$conn->query("UPDATE notes SET content = '$content', updated = $currentTime WHERE id = $id")) {
            $response['error'] = "Ошибка: " . $conn->error;
        }
    } else {
        if ($conn->query("INSERT INTO notes (content, updated, user_id) VALUES ('$content', $currentTime, $userId)") === TRUE) {
            $response['id'] = $conn->insert_id;
            $id = $response['id'];
            $hash = md5($id);
            $conn->query("UPDATE notes SET hash = '$hash' WHERE id = $id");
            $response['hash'] = $hash;
        } else {
            $response['error'] = "Ошибка: " . $conn->error;
        }
    }

    $conn->close();

    echo json_encode($response);

}

function checkIfExists($conn, $id): bool
{
    $result = $conn->query("SELECT id FROM notes WHERE id = '$id'");
    return ($result->num_rows > 0);
}

function changeVisibilityNote(): void
{
    $response = array();
    $response['error'] = null;
    $id = $_POST['id'];
    if ($id == "")
        $id = null;

    if ($_POST['status'] == "true")
        $status = 1;
    else
        $status = 0;

    if (is_numeric($id)) {
        $conn = connect();
        $id = mysqli_real_escape_string($conn, $id);
        if (!$conn->query("UPDATE notes SET visibility = $status WHERE id = $id")) {
            $response['error'] = "Ошибка: " . $conn->error;
        }
        $conn->close();
    } else
        $response['error'] = "Ошибка: неверный id";
    echo json_encode($response);
}
