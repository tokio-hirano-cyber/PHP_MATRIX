<?php
define('DB_HOST', 'mysql3112.db.sakura.ne.jp');
define('DB_NAME', 'neoris_php_matrix');
define('DB_USER', 'neoris_php_matrix');
define('DB_PASS', 'php123456');
define('DB_CHARSET', 'utf8mb4');

session_start();

function getDBConnection() {
    $max_retries = 3;
    $retry_count = 0;
    
    while ($retry_count < $max_retries) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false, 
                PDO::ATTR_TIMEOUT => 5, 
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            return $pdo;
            
        } catch (PDOException $e) {
            $retry_count++;
            
            if ($retry_count >= $max_retries) {
                $error_msg = "データベース接続エラー: " . $e->getMessage();
                $error_msg .= "<br><br>接続情報を確認してください：<br>";
                $error_msg .= "ホスト: " . DB_HOST . "<br>";
                $error_msg .= "データベース名: " . DB_NAME . "<br>";
                $error_msg .= "ユーザー名: " . DB_USER . "<br>";
                
                if (strpos($e->getMessage(), '2006') !== false || strpos($e->getMessage(), 'gone away') !== false) {
                    $error_msg .= "<br><br>「MySQL server has gone away」エラーの場合：<br>";
                    $error_msg .= "1. データベースが正しく作成されているか確認してください<br>";
                    $error_msg .= "2. ユーザー名とパスワードが正しいか確認してください<br>";
                    $error_msg .= "3. さくらサーバーのコントロールパネルでデータベース設定を確認してください";
                } elseif (strpos($e->getMessage(), '1045') !== false) {
                    $error_msg .= "<br><br>認証エラー：ユーザー名またはパスワードが正しくありません";
                } elseif (strpos($e->getMessage(), '1049') !== false) {
                    $error_msg .= "<br><br>データベースが見つかりません：データベース名を確認してください";
                }
                
                die($error_msg);
            }
            
            usleep(500000); 
        }
    }
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
?>

