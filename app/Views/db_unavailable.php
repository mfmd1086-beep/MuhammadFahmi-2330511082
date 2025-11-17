<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Unavailable</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#333; padding:40px; }
        .card { max-width:800px; margin:60px auto; text-align:center; }
        .error { color:#b00; }
        .actions { margin-top:20px; }
        a.button { display:inline-block; padding:10px 16px; background:#007bff; color:#fff; text-decoration:none; border-radius:4px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Database Tidak Tersedia</h1>
        <p class="error"><?php echo isset($error) ? htmlspecialchars($error) : 'Koneksi ke database gagal.'; ?></p>
        <p>Ikuti langkah ini untuk memperbaiki (lokal):</p>
        <ol style="text-align:left; display:inline-block;">
            <li>Jalankan <code>/db_setup.php</code> di browser untuk membuat database dan tabel.</li>
            <li>Jalankan <code>/db_test.php</code> untuk memverifikasi koneksi.</li>
            <li>Jika Anda punya dump SQL, import ke database <code><?php echo htmlspecialchars($db ?? 'fahnicv'); ?></code>.</li>
        </ol>
        <div class="actions">
            <a class="button" href="/db_setup.php">Jalankan DB Setup</a>
            &nbsp;
            <a class="button" href="/db_test.php">Tes Koneksi</a>
        </div>
    </div>
</body>
</html>
