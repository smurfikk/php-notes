<?php
function connect(): mysqli
{
    $conn = mysqli_connect(
        "127.0.0.1",
        "root",
        "root",
        "notes",
        "3306"
    );
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }
    return $conn;
}

function getArrayNotes($userId): array
{
    $conn = connect();
    $notes = array();
    $result = $conn->query("SELECT id, title, content FROM notes WHERE user_id = $userId ORDER BY updated DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row['title'];
            if (!$name)
                $name = substr($row['content'], 0, strpos($row['content'], "\n") ?: strlen($row['content']));
            if ($name == "") {
                $name = "Без названия";
            }
            $notes[] = array(
                'id' => $row['id'],
                'name' => $name,
                'content' => $row['content'],
            );
        }
    }
    $conn->close();
    return $notes;
}

function getNote($id): array|null
{
    if ($id == null)
        return null;
    $conn = connect();
    $escapedId = mysqli_real_escape_string($conn, $id);

    $result = $conn->query("SELECT id, title, content, hash, visibility FROM notes WHERE id = '$escapedId'");

    if ($result && $result->num_rows > 0) {
        $record = $result->fetch_assoc();
        $conn->close();
        return $record;
    }
    $conn->close();
    return null;
}

function getNoteByHash($hash): array|null
{
    if ($hash == null)
        return null;
    $conn = connect();
    $escapedId = mysqli_real_escape_string($conn, $hash);

    $result = $conn->query("SELECT id, title, content, hash, visibility FROM notes WHERE hash = '$hash'");

    if ($result && $result->num_rows > 0) {
        $record = $result->fetch_assoc();
        $conn->close();
        return $record;
    }
    $conn->close();
    return null;
}

function checkCredentials($login, $password): int|null
{
    $conn = connect();
    $login = mysqli_real_escape_string($conn, $login);
    $password = md5($password);

    $result = $conn->query("SELECT id FROM user WHERE login = '$login' AND password = '$password'");
    if ($result && $result->num_rows > 0) {
        $record = $result->fetch_assoc();
        $conn->close();
        return $record["id"];
    }
    $conn->close();
    return null;
}

function register($login, $password): int|null
{
    $conn = connect();
    $login = mysqli_real_escape_string($conn, $login);
    $password = md5($password);

    $result = $conn->query("SELECT id FROM user WHERE login = '$login'");
    if ($result && $result->num_rows > 0) {
        $conn->close();
        return null;
    }
    $conn->query("INSERT INTO user (login, password) VALUES ('$login', '$password')");
    $id = $conn->insert_id;
    $conn->close();
    return $id;

}

function getUser($id): string|null
{
    if ($id == null)
        return null;
    $conn = connect();
    $escapedId = mysqli_real_escape_string($conn, $id);

    $result = $conn->query("SELECT login FROM user WHERE id = '$escapedId'");

    if ($result && $result->num_rows > 0) {
        $record = $result->fetch_assoc();
        $conn->close();
        return $record["login"];
    }
    $conn->close();
    return null;
}

function getAllUsers(): array
{
    $conn = connect();
    $users = array();
    $result = $conn->query("SELECT id, login FROM user");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = array(
                'id' => $row['id'],
                'login' => $row['login'],
            );
        }
    }
    $conn->close();
    return $users;
}

function getAllNotes(): array
{
    $conn = connect();
    $notes = array();
    $result = $conn->query("SELECT id, user_id, hash FROM notes");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notes[] = array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'hash' => $row['hash'],
            );
        }
    }
    $conn->close();
    return $notes;
}
