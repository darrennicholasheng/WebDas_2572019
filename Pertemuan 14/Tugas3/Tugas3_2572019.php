<!--2572019-Darren Nicholas Heng-->
<?php
include 'koneksi.php';

$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $asal = $_POST['asal'];
    $komentar = $_POST['komentar'];

    if (empty($nama) || empty($asal) || empty($komentar)) {
        $pesan_error = "Semua field harus diisi!";
    } else {
        try {
            $sql_insert = "INSERT INTO buku_tamu (nama, asal, komentar) VALUES (:nama, :asal, :komentar)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->bindParam(':nama', $nama);
            $stmt_insert->bindParam(':asal', $asal);
            $stmt_insert->bindParam(':komentar', $komentar);
            $stmt_insert->execute();
            
            $pesan_sukses = "Komentar berhasil ditambahkan!";
        } catch (PDOException $e) {
            $pesan_error = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}
 
$query_select = "SELECT * FROM buku_tamu ORDER BY waktu DESC";
$stmt = $pdo->query($query_select);
$total_komentar = $stmt->rowCount();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuTamu - [2572019]</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f5f5;
            padding: 2rem 1rem;
            color: #333;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        h2 {
            margin-top: 0;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .btn-submit {
            background-color: #7B3AED; 
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-submit:hover {
            background-color: #6D28D9;
        }
        .divider {
            border: 0;
            border-top: 1px solid #eaeaea;
            margin: 2rem 0;
        }
        .comments-section h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .comments-section h3 span {
            color: #888;
            font-size: 0.8rem;
            font-weight: normal;
        }
        .comment-card {
            padding: 1rem;
            border: 1px solid #eaeaea;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            align-items: center;
        }
        .fw-bold {
            font-weight: 700;
            font-size: 0.9rem;
        }
        .text-muted {
            color: #888;
            font-size: 0.75rem;
        }
        .comment-body {
            font-size: 0.9rem;
            color: #555;
        }
        .alert {
            padding: 0.8rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .alert.error {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .alert.success {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eaeaea;
            text-align: left;
        }
    </style>

</head>
<body>
    <div class="container">
        <h2>Buku Tamu</h2>
        
        <?php if (!empty($pesan_error)) { ?>
            <div class="alert error"><?php echo $pesan_error; ?></div>
        <?php } ?>
        
        <?php if (!empty($pesan_sukses)) { ?>
            <div class="alert success"><?php echo $pesan_sukses; ?></div>
        <?php } ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" placeholder="Nama lengkap kamu">
            </div>
            <div class="form-group">
                <label>Asal Kota</label>
                <input type="text" name="asal" placeholder="Contoh: Bandung">
            </div>
            <div class="form-group">
                <label>Komentar</label>
                <textarea name="komentar" rows="4" placeholder="Tulis komentar atau kesanmu..."></textarea>
            </div>
            <button type="submit" class="btn-submit">Kirim Komentar</button>
        </form>

        <hr class="divider">

        <div class="comments-section">
            <h3>Komentar Tamu <span>(<?php echo $total_komentar; ?> komentar)</span></h3>

            <?php if ($total_komentar > 0) { ?>
                <div class="comments-list">
                    <?php 
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <span class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></span>
                                <span class="text-muted"><?php echo htmlspecialchars($row['asal']); ?> | <?php echo htmlspecialchars($row['waktu']); ?></span>
                            </div>
                            <div class="comment-body">
                                "<?php echo htmlspecialchars($row['komentar']); ?>"
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
            <?php } else { ?>
                <p class="text-muted">Belum ada komentar</p>
            <?php } ?>
        </div>
        
        <div class="footer text-muted">
            [2572019] - [Darren Nicholas Heng]
        </div>
    </div>
</body>
</html>