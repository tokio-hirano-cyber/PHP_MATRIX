<?php
require_once 'config.php';
checkLogin();

$pdo = getDBConnection();

$search = $_GET['search'] ?? '';
$where = '';
$params = [];

if (!empty($search)) {
    $where = "WHERE name LIKE :search OR email LIKE :search OR skill LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

$sql = "SELECT * FROM engineers " . $where . " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$engineers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エンジニア管理システム</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        h1 {
            color: #333;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-success:hover {
            background: #229954;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background: #e67e22;
        }
        .search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-form input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .search-form button {
            padding: 10px 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-card h3 {
            font-size: 32px;
            margin-bottom: 5px;
        }
        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>エンジニア管理システム</h1>
            <div class="user-info">
                <span>ようこそ、<?php echo htmlspecialchars($_SESSION['username']); ?>さん</span>
                <a href="logout.php" class="btn btn-danger">ログアウト</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3><?php echo count($engineers); ?></h3>
                <p>登録エンジニア数</p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" placeholder="名前、メール、スキルで検索..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">検索</button>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="btn btn-warning">クリア</a>
                <?php endif; ?>
            </form>
            <a href="create.php" class="btn btn-success">新規追加</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>スキル</th>
                    <th>経験年数</th>
                    <th>登録日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($engineers)): ?>
                    <tr>
                        <td colspan="7" class="no-data">データがありません</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($engineers as $engineer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($engineer['id']); ?></td>
                            <td><?php echo htmlspecialchars($engineer['name']); ?></td>
                            <td><?php echo htmlspecialchars($engineer['email']); ?></td>
                            <td><?php echo htmlspecialchars($engineer['skill']); ?></td>
                            <td><?php echo htmlspecialchars($engineer['experience_years']); ?>年</td>
                            <td><?php echo htmlspecialchars($engineer['created_at']); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=<?php echo $engineer['id']; ?>" class="btn btn-warning btn-sm">編集</a>
                                    <a href="delete.php?id=<?php echo $engineer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('本当に削除しますか？')">削除</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

