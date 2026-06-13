<!--2572019-Darren Nicholas Heng-->
<?php
session_start();
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "latihan_login";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$error_msg = "";
$success_msg = "";

$page = isset($_GET['page']) ? $_GET['page'] : 'login';


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: Tugas2_2572019.php");
    exit();
}


if (isset($_POST['btn_register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $cek = $conn->query("SELECT * FROM users WHERE email='$email' OR username='$username'");
    if ($cek->num_rows > 0) {
        $row = $cek->fetch_assoc();
        if ($row['email'] == $email) {
            $error_msg = "Email sudah terdaftar.";
        } else {
            $error_msg = "Username sudah terdaftar.";
        }
        $page = 'register'; 
    } else {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashPassword')");
        
        $success_msg = "Data sudah disimpan. Silahkan login.";
        $page = 'login';
    }
}

if (isset($_POST['btn_login'])) {
    $identity = $conn->real_escape_string($_POST['identity']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$identity' OR username='$identity'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: Tugas2_2572019.php");
            exit();
        } else {
            $error_msg = "Password salah!";
        }
    } else {
        $error_msg = "Email / Username tidak ditemukan!";
    }
    $page = 'login';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoginRegister - [2572019]</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card-box {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #eaeaea;
            border-radius: 8px;
            background-color: #fff;
        }
        .form-label {
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 0.3rem;
        }
        .dashboard-box {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <?php 
    if (isset($_SESSION['username'])) { ?>
        <div class="dashboard-box">
            <div class="alert alert-success d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></h5>
            </div>
            <a href="?action=logout" class="btn btn-danger">Logout</a>
        </div>
    <?php } else { ?>
        <?php if ($page == 'register') { ?>
            <div class="card-box">
                <h3 class="text-center mb-4">Register</h3>

                <?php if ($error_msg) { ?>
                    <div class="alert alert-danger text-center"><?= $error_msg ?></div>
                <?php } ?>

                <form method="POST" action="Tugas2_2572019.php?page=register">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="btn_register" class="btn btn-primary w-100 mb-3">Register</button>
                    <div class="text-center">
                        <span class="small text-muted">Sudah punya akun? </span>
                        <a href="?page=login" class="small text-decoration-none">Login</a>
                    </div>
                </form>
            </div>
        <?php } else { ?>
            <div class="card-box">
                <h3 class="text-center mb-4">Login</h3>

                <?php if ($success_msg) { ?>
                    <div class="alert alert-success text-center"><?= $success_msg ?></div>
                <?php } ?>

                <?php if ($error_msg) { ?>
                    <div class="alert alert-danger text-center"><?= $error_msg ?></div>
                <?php } ?>

                <form method="POST" action="Tugas2_2572019.php?page=login">
                    <div class="mb-3">
                        <label class="form-label">Email / Username</label>
                        <input type="text" name="identity" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="btn_login" class="btn btn-success w-100 mb-3">Login</button>
                    <div class="text-center">
                        <span class="small text-muted">Belum punya akun? </span>
                        <a href="?page=register" class="small text-decoration-none">Register</a>
                    </div>
                </form>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>