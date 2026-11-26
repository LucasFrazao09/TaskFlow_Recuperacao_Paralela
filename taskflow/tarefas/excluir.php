<?php
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    if ($id) {
        $q = $pdo->prepare('DELETE FROM tarefas WHERE id = ?');
        $q->execute([$id]);
    }
}

header('Location: index.php');
exit;