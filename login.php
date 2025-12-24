<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Bersihkan input
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['status'] = "login";
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['nama'] = $data['name'];
        
        echo "<script>
                alert('Login Berhasil! Selamat datang " . $data['name'] . "');
                document.location.href = 'index.html';
              </script>";
    } else {
        // Pesan Error jika akun tidak ditemukan
        $error = "Akun tidak ditemukan atau Password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login User</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-right: 200px;
            background-image: url('baground login.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            width: 350px;
            height: 440px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        h2 {
            text-align: center;
            color: #ED7A13;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border 0.3s, box-shadow 0.3s;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        input:focus {
            border-color: #ED7A13;
            outline: none;
            box-shadow: 0 0 0 2px rgba(237, 122, 19, 0.2);
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #ED7A13;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s, transform 0.2s;
        }
        
        button:hover {
            background: #d2690e;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
            padding: 8px;
            background-color: rgba(255, 235, 238, 0.9);
            border-radius: 4px;
            border-left: 4px solid #e74c3c;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .register-link a {
            color: #ED7A13;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .register-link a:hover {
            color: #d2690e;
            text-decoration: underline;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
        }
        
        .forgot-password a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .forgot-password a:hover {
            color: #ED7A13;
            text-decoration: underline;
        }
        
        label {
            font-weight: 500;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        
        @media (max-width: 480px) {
            .login-box {
                width: 90%;
                margin: 0 20px;
                padding: 25px 20px;
            }
            body {
                padding: 20px;
                align-items: flex-start;
                padding-top: 50px;
                justify-content: center;
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Masuk</h2>

        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="" method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan Password" required>

            <button type="submit" name="login">Masuk</button>
        </form>

        <div class="forgot-password">
            <a href="#">Lupa password?</a>
        </div>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>
</html>