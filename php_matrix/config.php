<?php
// データベース接続設定
define('DB_HOST', 'mysql3112.db.sakura.ne.jp');
define('DB_NAME', 'neoris_php_matrix');
define('DB_USER', 'neoris_php_matrix');
define('DB_PASS', 'php123456');
define('DB_CHARSET', 'utf8mb4');

// セッション開始
session_start();

// データベース接続関数（さくらサーバー向けに最適化）
function getDBConnection() {
    // さくらサーバーでは毎回新しい接続を作成する方が安全
    $max_retries = 3;
    $retry_count = 0;
    
    while ($retry_count < $max_retries) {
        try {
            // ポート番号を明示的に指定（さくらサーバーは通常3306）
            $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false, // 永続接続は無効
                PDO::ATTR_TIMEOUT => 5, // 接続タイムアウトを短く設定
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // 接続が成功したら即座に返す（SET SESSIONは実行しない）
            return $pdo;
            
        } catch (PDOException $e) {
            $retry_count++;
            
            // 最後の試行でも失敗した場合
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
            
            // リトライ前に少し待機
            usleep(500000); // 0.5秒待機
        }
    }
}

// ログインチェック関数
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
?>
