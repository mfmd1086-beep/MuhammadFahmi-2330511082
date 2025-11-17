<?php
// Simple DB creation helper for local development.
// WARNING: Only use locally on development environment. Do NOT expose on production.

function parse_env_file($path)
{
    if (!is_file($path)) return [];
    $contents = file_get_contents($path);
    $lines = preg_split('/\r\n|\n|\r/', $contents);
    $out = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (preg_match('/^database\.default\.database\s*=\s*(?:\'([^']*)\'|"([^"]*)"|([^#]*))/i', $line, $m)) {
            $out['database.default.database'] = isset($m[1]) && $m[1] !== '' ? $m[1] : (isset($m[2]) && $m[2] !== '' ? $m[2] : trim($m[3]));
        }
        if (preg_match('/^database\.default\.hostname\s*=\s*(?:\'([^']*)\'|"([^"]*)"|([^#]*))/i', $line, $m)) {
            $out['database.default.hostname'] = isset($m[1]) && $m[1] !== '' ? $m[1] : (isset($m[2]) && $m[2] !== '' ? $m[2] : trim($m[3]));
        }
        if (preg_match('/^database\.default\.username\s*=\s*(?:\'([^']*)\'|"([^"]*)"|([^#]*))/i', $line, $m)) {
            $out['database.default.username'] = isset($m[1]) && $m[1] !== '' ? $m[1] : (isset($m[2]) && $m[2] !== '' ? $m[2] : trim($m[3]));
        }
        if (preg_match('/^database\.default\.password\s*=\s*(?:\'([^']*)\'|"([^"]*)"|([^#]*))/i', $line, $m)) {
            $out['database.default.password'] = isset($m[1]) && $m[1] !== '' ? $m[1] : (isset($m[2]) && $m[2] !== '' ? $m[2] : trim($m[3]));
        }
        if (preg_match('/^database\.default\.port\s*=\s*(\d+)/i', $line, $m)) {
            $out['database.default.port'] = (int) $m[1];
        }
    }
    return $out;
}

// get defaults from app/Config/Database.php by simple parsing
function parse_config_database($path)
{
    if (!is_file($path)) return [];
    $contents = file_get_contents($path);
    $out = [];
    if (preg_match("/'database'\s*=>\s*'([^']*)'/", $contents, $m)) $out['database'] = $m[1];
    if (preg_match("/'hostname'\s*=>\s*'([^']*)'/", $contents, $m)) $out['hostname'] = $m[1];
    if (preg_match("/'username'\s*=>\s*'([^']*)'/", $contents, $m)) $out['username'] = $m[1];
    if (preg_match("/'password'\s*=>\s*'([^']*)'/", $contents, $m)) $out['password'] = $m[1];
    if (preg_match("/'port'\s*=>\s*(\d+)/", $contents, $m)) $out['port'] = (int) $m[1];
    return $out;
}

$projectRoot = dirname(__DIR__);
$env = parse_env_file($projectRoot . DIRECTORY_SEPARATOR . 'env');
$config = parse_config_database($projectRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Database.php');

$dbName = $env['database.default.database'] ?? $config['database'] ?? 'fahmicv';
$host   = $env['database.default.hostname'] ?? $config['hostname'] ?? '127.0.0.1';
$user   = $env['database.default.username'] ?? $config['username'] ?? 'root';
$pass   = $env['database.default.password'] ?? $config['password'] ?? '';
$port   = $env['database.default.port'] ?? $config['port'] ?? 3306;

header('Content-Type: text/html; charset=utf-8');
echo "<h2>DB Create Helper</h2>";
echo "<p>Host: <strong>" . htmlspecialchars($host) . "</strong>, User: <strong>" . htmlspecialchars($user) . "</strong>, Port: <strong>" . htmlspecialchars($port) . "</strong></p>";
echo "<p>Database to create: <strong>" . htmlspecialchars($dbName) . "</strong></p>";

// connect without selecting a database
$mysqli = @new mysqli($host, $user, $pass, '', $port);
if ($mysqli->connect_errno) {
    echo "<p style='color:red'>Gagal terhubung ke server MySQL: (" . htmlspecialchars($mysqli->connect_errno) . ") " . htmlspecialchars($mysqli->connect_error) . "</p>";
    exit;
}

$created = $mysqli->query("CREATE DATABASE IF NOT EXISTS `" . $mysqli->real_escape_string($dbName) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
if ($created) {
    echo "<p style='color:green'>Database '<strong>" . htmlspecialchars($dbName) . "</strong>' berhasil dibuat atau sudah ada.</p>";
} else {
    echo "<p style='color:red'>Gagal membuat database: " . htmlspecialchars($mysqli->error) . "</p>";
}

// Optional: show databases
$res = $mysqli->query("SHOW DATABASES LIKE '" . $mysqli->real_escape_string($dbName) . "'");
if ($res && $res->num_rows > 0) {
    echo "<p>Konfirmasi: database ditemukan.</p>";
} else {
    echo "<p>Konfirmasi: database tidak ditemukan setelah percobaan pembuatan.</p>";
}

echo "<hr><p>Selanjutnya: buka <code>/db_test.php</code> untuk menguji koneksi dan tabel. Jika Anda memiliki dump SQL, upload ke folder project dan jalankan import lewat phpMyAdmin atau MySQL CLI:</p>";
echo "<pre>mysql -u root -p fahnicv &lt; path/to/dump.sql</pre>";

$mysqli->close();

?>
