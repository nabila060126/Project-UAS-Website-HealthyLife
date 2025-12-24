<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    // Ambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $height = $_POST['height_cm'];
    $weight = $_POST['weight_kg'];

    // Mencegah error jika input mengandung karakter aneh
    $name = mysqli_real_escape_string($conn, $name);
    
    // Cek apakah email sudah pernah dipakai?
    $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar! Silakan login.');</script>";
    } else {
        // Query Simpan Data ke Database
        $query = "INSERT INTO users (name, email, password, gender, birth_date, height_cm, weight_kg) 
                  VALUES ('$name', '$email', '$password', '$gender', '$birth_date', '$height', '$weight')";

        if (mysqli_query($conn, $query)) {
            echo "<script>
                    alert('Pendaftaran Berhasil! Silakan Login.');
                    document.location.href = 'login.php';
                  </script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi User</title>
   <style>
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px 200px 20px 20px; /* Padding kanan 100px, lainnya 20px */
        
        /* Background gambar */
        background-image: url('baground login.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .register-box { 
        background: rgba(255, 255, 255, 0.98);
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        width: 380px;
        max-width: 100%;
    }
    
    h2 { 
        text-align: center;
        margin-bottom: 25px;
        margin-top: 0;
        color: #ED7A13;
        font-size: 24px;
        font-weight: 600;
    }
    
    label {
        display: block;
        font-weight: 500;
        color: #333;
        margin-bottom: 6px;
        font-size: 14px;
    }
    
    input, select { 
        width: 100%;
        padding: 12px;
        margin-bottom: 18px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border 0.3s, box-shadow 0.3s;
        background-color: #fafafa;
    }
    
    input:focus, select:focus {
        border-color: #ED7A13;
        outline: none;
        box-shadow: 0 0 0 2px rgba(237, 122, 19, 0.2);
        background-color: white;
    }
    
    /* Untuk input tinggi dan berat yang berdampingan */
    .input-row {
        display: flex;
        gap: 15px;
        margin-bottom: 18px;
    }
    
    .input-row > div {
        flex: 1;
    }
    
    .input-row input {
        margin-bottom: 0;
    }
    
    button { 
        width: 100%;
        padding: 13px;
        background: #ED7A13;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        margin-top: 10px;
        transition: background 0.3s, transform 0.2s;
    }
    
    button:hover { 
        background: #d2690e;
        transform: translateY(-2px);
    }
    
    button:active {
        transform: translateY(0);
    }
    
    .back-link { 
        display: block;
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        text-decoration: none;
        color: #666;
        transition: color 0.3s;
    }
    
    .back-link:hover {
        color: #ED7A13;
        text-decoration: underline;
    }
    
    /* Responsif untuk tablet */
    @media (max-width: 768px) {
        body {
            justify-content: center;
            padding: 20px;
        }
        
        .register-box {
            width: 100%;
            max-width: 450px;
        }
    }
    
    /* Responsif untuk mobile */
    @media (max-width: 480px) {
        body {
            padding: 15px;
            align-items: flex-start;
            padding-top: 30px;
        }
        
        .register-box {
            width: 100%;
            padding: 25px 20px;
        }
        
        h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .input-row {
            flex-direction: column;
            gap: 0;
        }
        
        .input-row input {
            margin-bottom: 18px;
        }
    }
</style>
</head>
<body>

    <div class="register-box">
        <h2>Buat Akun Baru</h2>
        
        <form action="" method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="name" required placeholder="Nama Anda">

            <label>Email</label>
            <input type="email" name="email" required placeholder="email@contoh.com">

            <label>Password</label>
            <input type="password" name="password" required placeholder="Password">

            <label>Jenis Kelamin</label>
            <select name="gender" required>
                <option value="">-- Pilih --</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            <label>Tanggal Lahir</label>
            <input type="date" name="birth_date" required>

            <div style="display: flex; gap: 10px;">
                <div>
                    <label>Tinggi (cm)</label>
                    <input type="number" name="height_cm" placeholder="170" required>
                </div>
                <div>
                    <label>Berat (kg)</label>
                    <input type="number" name="weight_kg" placeholder="60" required>
                </div>
            </div>

            <button type="submit" name="register">Daftar Sekarang</button>
        </form>

        <a href="login.php" class="back-link">Sudah punya akun? Login di sini</a>
    </div>

</body>
</html>