<?php
require_once 'config.php';
checkLogin();

$pdo = getDBConnection();

$id = $_GET['id'] ?? 0;

if (empty($id)) {
    header('Location: index.php');
    exit;
}

try {
    $sql = "DELETE FROM engineers WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    header('Location: index.php?deleted=1');
    exit;
} catch (PDOException $e) {
    die("削除エラー: " . $e->getMessage());
}
?>

