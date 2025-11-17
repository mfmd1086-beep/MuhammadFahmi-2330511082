<?php
// Lightweight DB connection tester for this project.
// Place this file in the project's `public/` folder and open it in the browser.

function parse_env_file($path)
{
    if (!is_file($path)) {
        return [];
    }

    $out = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }

        // split on the first '=' and trim
        $parts = preg_split('/\s*=\s*/', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = $parts[0];
        $val = $parts[1];
        $val = trim($val);

        // remove surrounding single or double quotes if present
        if (strlen($val) >= 2) {
            $first = $val[0];
            $last = substr($val, -1);
            if (($first === "'" && $last === "'") || ($first === '"' && $last === '"')) {
                $val = substr($val, 1, -1);
            }
        }

        $out[$key] = $val;
    }

    return $out;
}

function parse_config_database($path)
{
    if (!is_file($path)) return [];
    $contents = file_get_contents($path);
    $out = [];

    if (preg_match("/'database'\s*=>\s*'([^']*)'/", $contents, $m)) {
        $out['database'] = $m[1];
    }
    if (preg_match("/'hostname'\s*=>\s*'([^']*)'/", $contents, $m2)) {
        $out['hostname'] = $m2[1];
    }
    if (preg_match("/'username'\s*=>\s*'([^']*)'/", $contents, $m3)) {
        $out['username'] = $m3[1];
    }
    if (preg_match("/'password'\s*=>\s*'([^']*)'/", $contents, $m4)) {
        $out['password'] = $m4[1];
    }
    if (preg_match("/'port'\s*=>\s*(\d+)/", $contents, $m5)) {
        $out['port'] = (int) $m5[1];
    }

    return $out;
}

$projectRoot = dirname(__DIR__);
$envPath = $projectRoot . DIRECTORY_SEPARATOR . 'env';
$configPath = $projectRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Database.php';

$env = parse_env_file($envPath);
$config = parse_config_database($configPath);

$host = $env['database.default.hostname'] ?? $config['hostname'] ?? ($env['DB_HOST'] ?? '127.0.0.1');
$user = $env['database.default.username'] ?? $config['username'] ?? ($env['DB_USERNAME'] ?? 'root');
$pass = $env['database.default.password'] ?? $config['password'] ?? ($env['DB_PASSWORD'] ?? '');
$port = $env['database.default.port'] ?? $config['port'] ?? ($env['DB_PORT'] ?? 3306);

$candidates = [];
if (!empty($env['database.default.database'])) $candidates[] = $env['database.default.database'];
if (!empty($config['database'])) $candidates[] = $config['database'];
// add a few typical guesses
$candidates[] = 'fahmicv';
$candidates[] = 'fahmicv';

$candidates = array_values(array_unique(array_filter($candidates)));

header('Content-Type: text/html; charset=utf-8');
echo "<h2>DB connection tester</h2>";
echo "<p><strong>Host:</strong> " . htmlspecialchars($host) . " &nbsp; <strong>User:</strong> " . htmlspecialchars($user) . " &nbsp; <strong>Port:</strong> " . htmlspecialchars($port) . "</p>";

if (empty($candidates)) {
    echo "<p>No database names discovered in `env` or `app/Config/Database.php`. Edit `env` or `app/Config/Database.php` to set the database name.</p>";
    exit;
}

foreach ($candidates as $db) {
    echo "<h3>Trying database: " . htmlspecialchars($db) . "</h3>";

    $mysqli = @new mysqli($host, $user, $pass, $db, $port);
    if ($mysqli->connect_errno) {
        echo "<p style='color:red'>Koneksi gagal: (" . htmlspecialchars($mysqli->connect_errno) . ") " . htmlspecialchars($mysqli->connect_error) . "</p>";
    } else {
        echo "<p style='color:green'>Koneksi berhasil ke database '<strong>" . htmlspecialchars($db) . "</strong>'.</p>";
        try {
            // Some MySQL versions don't accept LIMIT on SHOW TABLES; run plain SHOW TABLES
            $res = $mysqli->query("SHOW TABLES");
            if ($res && $res->num_rows > 0) {
                echo "<p>Beberapa tabel: <ul>";
                $count = 0;
                while ($r = $res->fetch_array()) {
                    echo '<li>' . htmlspecialchars($r[0]) . '</li>';
                       $count++;
                    if ($count >= 20) break; // keep output short
                }
                echo "</ul></p>";
            } else {
                echo "<p>Tidak ada tabel atau tidak dapat membaca tabel (mungkin kosong).</p>";
            }
        } catch (\mysqli_sql_exception $e) {
            echo "<p style='color:red'>Query error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        $mysqli->close();
    }
}

echo "<hr><p>Jika semua gagal: periksa detail koneksi di file `env` (root project) atau `app/Config/Database.php` dan sesuaikan nama database, user, dan password. Setelah mengubah `env`, restart server (Laragon) atau jalankan server built-in kembali.</p>";

?>
