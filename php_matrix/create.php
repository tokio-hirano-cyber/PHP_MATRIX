<?php
require_once 'config.php';
checkLogin();

$pdo = getDBConnection();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $skill = $_POST['skill'] ?? '';
    $experience_years = $_POST['experience_years'] ?? 0;

    if (empty($name) || empty($email) || empty($skill)) {
        $error = 'すべての必須項目を入力してください';
    } else {
        try {
            $sql = "INSERT INTO engineers (name, email, skill, experience_years, created_at) 
                    VALUES (:name, :email, :skill, :experience_years, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':skill' => $skill,
                ':experience_years' => $experience_years
            ]);
            $success = 'エンジニアを追加しました';
            header('Location: index.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = 'エラーが発生しました: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規追加 - エンジニア管理システム</title>
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
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        .error {
            color: #e74c3c;
            padding: 10px;
            background: #ffeaea;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            color: #27ae60;
            padding: 10px;
            background: #eafaf1;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>新規エンジニア追加</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">名前 <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">メールアドレス <span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="skill">スキル <span style="color: red;">*</span></label>
                <input type="text" id="skill" name="skill" required value="<?php echo htmlspecialchars($_POST['skill'] ?? ''); ?>" placeholder="例: PHP, JavaScript, Python">
            </div>
            
            <div class="form-group">
                <label for="experience_years">経験年数</label>
                <input type="number" id="experience_years" name="experience_years" min="0" value="<?php echo htmlspecialchars($_POST['experience_years'] ?? 0); ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">追加</button>
                <a href="index.php" class="btn btn-secondary">キャンセル</a>
            </div>
        </form>
    </div>
</body>
</html>
