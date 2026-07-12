<?php
/****************************************************
 * PKPXMLParser Renamer Locker
 * Lock = rename ke PKPXMLParser.php- 
 * Unlock = restore ke PKPXMLParser.php
 ****************************************************/

$PASSWORD_HASH = '$2a$12$FO.t.v/pScf7TshzRUPvIOx4KaevFZt19M5I3m8Rrg4XyFem6a/xq'; // GANTI

$originalFile = "/home/univgres/journal.univgresik.ac.id/lib/pkp/classes/core/PKPXMLParser.php";
$lockedFile   = "/home/univgres/journal.univgresik.ac.id/lib/pkp/classes/core/PKPXMLParser.php-";

if (!isset($_GET['key']) || !isset($_GET['mode']) || !password_verify($_GET['key'], $PASSWORD_HASH)) {
    http_response_code(404);
    exit("Not Found");
}

$mode = strtolower($_GET['mode']);

function lockParser() {
    global $originalFile, $lockedFile;
    
    if (file_exists($originalFile)) {
        if (rename($originalFile, $lockedFile)) {
            echo "PKPXMLParser renamed to .php- (LOCKED)<br>";
        }
    }
    
    // Extra protection
    $htaccess = "<FilesMatch \"\\.(xml|dtd|xsd)$\">\n    Deny from all\n</FilesMatch>";
    @file_put_contents("	/home/univgres/journal.univgresik.ac.id/plugins/importexport/native/.htaccess", $htaccess);
    
    echo "✅ NATIVE XML PARSER LOCKED";
}

function unlockParser() {
    global $originalFile, $lockedFile;
    
    if (file_exists($lockedFile)) {
        if (rename($lockedFile, $originalFile)) {
            echo "PKPXMLParser restored (UNLOCKED)<br>";
        }
    }
    
    echo "⚠️ NATIVE XML PARSER UNLOCKED";
}

if ($mode === "lock") {
    lockParser();
    exit;
}

if ($mode === "unlock") {
    unlockParser();
    exit;
}

http_response_code(404);
exit("Invalid mode");