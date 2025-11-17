<?php
// Development helper: create database + required tables and a sample biodata row.
// WARNING: For local development only. Do NOT expose on production.

function get_env($key, $default = null)
{
    if (function_exists('env')) {
        $v = env($key);
        if ($v !== null) return $v;
    }

    $root = dirname(__DIR__);
    $envPath = $root . DIRECTORY_SEPARATOR . 'env';
    if (!is_file($envPath)) return $default;

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        $parts = preg_split('/\s*=\s*/', $line, 2);
        if (count($parts) !== 2) continue;
        if ($parts[0] === $key) {
            $val = trim($parts[1]);
            if (strlen($val) >= 2) {
                $first = $val[0];
                $last = substr($val, -1);
                if (($first === "'" && $last === "'") || ($first === '"' && $last === '"')) {
                    $val = substr($val, 1, -1);
                }
            }
            return $val;
        }
    }

    return $default;
}

$host = get_env('database.default.hostname', '127.0.0.1');
$user = get_env('database.default.username', 'root');
$pass = get_env('database.default.password', '');
$port = (int) get_env('database.default.port', 3306);
$db   = get_env('database.default.database', 'fahnicv');

header('Content-Type: text/html; charset=utf-8');
echo "<h2>DB Setup (dev only)</h2>";
echo "<p>Connecting to MySQL at <strong>" . htmlspecialchars($host) . ":" . htmlspecialchars($port) . "</strong> as <strong>" . htmlspecialchars($user) . "</strong></p>";

$mysqli = @new mysqli($host, $user, $pass, '', $port);
if ($mysqli->connect_errno) {
    echo "<p style='color:red'>Gagal koneksi ke MySQL: (" . htmlspecialchars($mysqli->connect_errno) . ") " . htmlspecialchars($mysqli->connect_error) . "</p>";
    exit;
}

// create database
if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `" . $mysqli->real_escape_string($db) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    echo "<p style='color:red'>Gagal membuat database: " . htmlspecialchars($mysqli->error) . "</p>";
    exit;
}
echo "<p style='color:green'>Database <strong>" . htmlspecialchars($db) . "</strong> ready.</p>";

$mysqli->select_db($db);

$statements = [
    // biodata
    "CREATE TABLE IF NOT EXISTS `biodata` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `nama` VARCHAR(255) DEFAULT NULL,
        `ttl` VARCHAR(255) DEFAULT NULL,
        `alamat` TEXT,
        `email` VARCHAR(255) DEFAULT NULL,
        `telepon` VARCHAR(50) DEFAULT NULL,
        `deskripsi` TEXT,
        `foto` VARCHAR(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // pendidikan
    "CREATE TABLE IF NOT EXISTS `pendidikan` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `jenjang` VARCHAR(100) DEFAULT NULL,
        `institusi` VARCHAR(255) DEFAULT NULL,
        `tahun_mulai` INT DEFAULT NULL,
        `tahun_selesai` INT DEFAULT NULL,
        `keterangan` TEXT,
        `biodata_id` INT UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // pengalaman
    "CREATE TABLE IF NOT EXISTS `pengalaman` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `posisi` VARCHAR(255) DEFAULT NULL,
        `instansi` VARCHAR(255) DEFAULT NULL,
        `tahun_mulai` INT DEFAULT NULL,
        `tahun_selesai` INT DEFAULT NULL,
        `deskripsi` TEXT,
        `biodata_id` INT UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // keahlian
    "CREATE TABLE IF NOT EXISTS `keahlian` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `nama_keahlian` VARCHAR(255) DEFAULT NULL,
        `tingkat` VARCHAR(50) DEFAULT NULL,
        `biodata_id` INT UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // portofolio
    "CREATE TABLE IF NOT EXISTS `portofolio` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `judul` VARCHAR(255) DEFAULT NULL,
        `deskripsi` TEXT,
        `link` VARCHAR(255) DEFAULT NULL,
        `gambar` VARCHAR(255) DEFAULT NULL,
        `biodata_id` INT UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($statements as $sql) {
    if ($mysqli->query($sql)) {
        echo "<p style='color:green'>Tabel dibuat / sudah ada.</p>";
    } else {
        echo "<p style='color:red'>Gagal membuat tabel: " . htmlspecialchars($mysqli->error) . "</p>";
    }
}

// insert minimal biodata if not exists
$res = $mysqli->query("SELECT id FROM `biodata` WHERE id = 1 LIMIT 1");
if ($res && $res->num_rows === 0) {
    $stmt = $mysqli->prepare("INSERT INTO `biodata` (`nama`,`ttl`,`alamat`,`email`,`telepon`,`deskripsi`,`foto`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $nama = 'Nama Anda';
    $ttl = '01 Jan 1990';
    $alamat = 'Alamat contoh';
    $email = 'email@example.com';
    $telepon = '08123456789';
    $deskripsi = 'Deskripsi singkat tentang diri.';
    $foto = 'default.jpg';
    $stmt->bind_param('sssssss', $nama, $ttl, $alamat, $email, $telepon, $deskripsi, $foto);
    if ($stmt->execute()) {
        echo "<p style='color:green'>Data biodata contoh ditambahkan (id= " . $mysqli->insert_id . ").</p>";
    } else {
        echo "<p style='color:red'>Gagal menambahkan biodata contoh: " . htmlspecialchars($mysqli->error) . "</p>";
    }
    $stmt->close();
} else {
    echo "<p>Data biodata sudah ada.</p>";
}

echo "<hr><p>Setelah ini, buka <a href='db_test.php'>db_test.php</a> untuk menguji koneksi dan coba akses homepage.</p>";

$mysqli->close();

?>
