<?php
session_start();

// Cek status login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: login.php");
    exit;
}

include 'koneksi.php';

// 1. Logika Hapus Data
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $q_delete = "DELETE FROM daily_note WHERE note_id = '$id_hapus'";
    mysqli_query($conn, $q_delete);
    header("Location: jadwal.php");
    exit;
}

// 2. Logika Simpan Data
if (isset($_POST['simpan_semua'])) {
    $user_id = $_SESSION['user_id'];
    
    // DATA UTAMA
    $date = $_POST['note_date'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];

    $sql_note = "INSERT INTO daily_note (user_id, note_date, title, description, category, priority) 
                 VALUES ('$user_id', '$date', '$title', '$desc', '$category', '$priority')";
    
    if (mysqli_query($conn, $sql_note)) {
        $last_note_id = mysqli_insert_id($conn); // Ambil ID terakhir
        
        // A. MEAL LOG
        if (!empty($_POST['food_name'])) {
            $meal_time = $_POST['meal_time'];
            $meal_type = $_POST['meal_type'];
            $food_name = $_POST['food_name'];
            $calories = !empty($_POST['calories']) ? $_POST['calories'] : 0;
            $meal_notes = $_POST['meal_notes'];
            mysqli_query($conn, "INSERT INTO meal_log (user_id, meal_date, meal_time, meal_type, food_name, calories, notes) VALUES ('$user_id', '$date', '$meal_time', '$meal_type', '$food_name', '$calories', '$meal_notes')");
        }

        // B. WATER INTAKE
        if (!empty($_POST['amount_ml'])) {
            $w_time = $_POST['intake_time'];
            $amount = $_POST['amount_ml'];
            mysqli_query($conn, "INSERT INTO water_intake (user_id, intake_date, intake_time, amount_ml) VALUES ('$user_id', '$date', '$w_time', '$amount')");
        }

        // C. EXERCISE LOG
        if (!empty($_POST['exercise_type'])) {
            $ex_type = $_POST['exercise_type'];
            $start_time = $_POST['start_time'];
            $duration = !empty($_POST['duration_minutes']) ? $_POST['duration_minutes'] : 0;
            $intensity = $_POST['intensity'];
            mysqli_query($conn, "INSERT INTO exercise_log (user_id, exercise_date, start_time, exercise_type, duration_minutes, intensity) VALUES ('$user_id', '$date', '$start_time', '$ex_type', '$duration', '$intensity')");
        }

        // D. SLEEP LOG
        if (!empty($_POST['bedtime']) && !empty($_POST['wake_time'])) {
            $bed = $_POST['bedtime'];
            $wake = $_POST['wake_time'];
            $quality = $_POST['quality_rating'];
            $s_notes = $_POST['sleep_notes'];

            $t1 = strtotime($bed);
            $t2 = strtotime($wake);
            if ($t2 < $t1) { $t2 += 24 * 60 * 60; }
            $duration_hours = ($t2 - $t1) / 3600;

            mysqli_query($conn, "INSERT INTO sleep_log (user_id, sleep_date, bedtime, wake_time, duration_hours, quality_rating, notes) VALUES ('$user_id', '$date', '$bed', '$wake', '$duration_hours', '$quality', '$s_notes')");
        }

        // E. HEALTH GOAL
        if (!empty($_POST['goal_title'])) {
            $g_type = $_POST['goal_type'];
            $g_title = $_POST['goal_title'];
            $g_desc = $_POST['goal_desc'];
            $target_val = !empty($_POST['target_value']) ? $_POST['target_value'] : 0;
            $current_val = !empty($_POST['current_value']) ? $_POST['current_value'] : 0;
            $start_d = $_POST['start_date'];
            $target_d = $_POST['target_date'];
            $status = $_POST['goal_status'];
            mysqli_query($conn, "INSERT INTO health_goal (user_id, goal_type, goal_title, description, target_value, current_value, start_date, target_date, status) VALUES ('$user_id', '$g_type', '$g_title', '$g_desc', '$target_val', '$current_val', '$start_d', '$target_d', '$status')");
        }

        echo "<script>alert('Laporan Berhasil Disimpan!'); window.location='jadwal.php';</script>";
        exit;
    } else {
        $error_msg = mysqli_error($conn);
        echo "<script>alert('Error: $error_msg');</script>";
    }
}
?> 

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Sehat</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hero-jadwal {
            width: 100%;
            height: 800px;
            display: flex;
            align-items: center;
            padding-left: 70px;
            background-image: url("bg jadwal.png");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        
        .hero-jadwal .hero-text {
            max-width: 800px;
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.2;  
            margin-bottom: -20px;
        }

        .hero-text h1 {
            font-size: 5rem;
            color: var(--moss);
            line-height: 1.3;
            margin-bottom: 2px;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .hero-text .green {
            color: var(--herb);
            font-size: 3rem;
            font-family: 'Segoe UI', sans-serif;
            margin-bottom: 5px;
        }

        .hero-text .orange {
            color: var(--radiate);
            font-size: 5rem;
            font-family: 'Segoe UI', sans-serif;
            margin-bottom: 5px;
        }

        .hero-text p {
            margin-top: 10px;
            line-height: 2;
            color: #000000;
            text-align: justify;
            text-justify: inter-word;
            font-size: 1.1rem;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f7f6;
            color: #333;
            overflow-x: hidden;
        }

        .dashboard-container {
            max-width: 1600px;
            margin: 30px auto 40px auto;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ED7A13;
            padding: 30px 30px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .greeting h1 {
            font-size: 2rem;
            color: #ffffffff;
            margin-bottom: 5px;
        }

        .greeting p {
            color: #f4f4f4ff;
            font-size: 0.9rem;
        }

        /* Content Layout */
        .content-grid {
            display: grid;
            padding: 5px 100px;
            grid-template-columns: 1fr 450px;
            gap: 25px;
        }

        /* Schedule/History Section */
        .schedule {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .schedule h3 {
            font-size: 1.8rem;
            color: var(--moss);
            margin-bottom: 25px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Note Item - Card Style */
        .note-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: none;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            transition: all 0.3s ease;
        }

        .note-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .note-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .note-date {
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: inline-block;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .bg-Rendah { background: #d1ecf1; color: #0c5460; }
        .bg-Sedang { background: #fff3cd; color: #856404; }
        .bg-Tinggi { background: #f8d7da; color: #721c24; }

        .note-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }

        .note-text {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            font-style: italic;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid var(--primary);
        }

        /* Activity Boxes */
        .activity-box {
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            font-size: 0.9rem;
            border-left: 5px solid;
            transition: all 0.3s ease;
        }

        .activity-box:hover {
            transform: translateX(5px);
        }

        .box-header {
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.05rem;
        }

        .box-meal { 
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            border-color: #ffc107;
            color: #d35400;
        }

        .box-water {
            background: linear-gradient(135deg, #e1f5fe 0%, #b3e5fc 100%);
            border-color: #03a9f4;
            color: #0277bd;
        }

        .box-exercise {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-color: #4caf50;
            color: #2e7d32;
        }

        .box-sleep {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            border-color: #9c27b0;
            color: #7b1fa2;
        }

        .box-goal {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            border-color: #e53935;
            color: #c62828;
        }

        .detail-row {
            background: rgba(255, 255, 255, 0.7);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
            display: flex;
            align-items: flex-start;
            transition: all 0.2s ease;
        }

        .detail-row:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateX(3px);
        }

        .d-bullet {
            margin-right: 10px;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .d-content {
            flex: 1;
            line-height: 1.5;
        }

        .d-meta {
            font-size: 0.8rem;
            opacity: 0.85;
            margin-top: 3px;
        }

        .delete-btn {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 15px;
            padding: 12px;
            background: white;
            border: 2px solid #ffcccc;
            color: #e74c3c;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
            transform: scale(1.02);
        }

        /* Form Section */
        .notes {
            background: #FFFADD;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }

        .notes h2 {
            font-size: 1.8rem;
            color: #ED7A13;
            text-align: center;
            margin-bottom: 10px;
        }

        .notes > p {
            text-align: center;
            color: #000000ff;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .form-section {
            transition: all 0.3s ease;
        }

        #step2, #step3, #step4 {
            display: none;
        }

        .notes input,
        .notes select,
        .notes textarea {
            width: 100%;
            padding: 14px;
            margin-bottom: 12px;
            border-radius: 10px;
            border: 2px solid #ED7A13;
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .notes input:focus,
        .notes select:focus,
        .notes textarea:focus {
            outline: none;
            border-color: #ED7A13;
            box-shadow: 0 0 0 3px rgba(94, 181, 224, 0.1);
        }

        .notes textarea {
            resize: vertical;
            min-height: 80px;
        }

        .subtitle-form {
            background: #ED7A13;
            color: #ffffffff;
            padding: 10px 15px;
            border-radius: 10px;
            margin: 15px 0 10px 0;
            font-weight: 700;
            border-left: 5px solid var(--success);
            font-size: 0.95rem;
        }

        .small-label {
            font-size: 0.75rem;
            color: #666;
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .btn-group {
            display: flex;
            background:#ED7A13;
            border-radius: 50px;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-next,
        .btn-submit {
            background: linear-gradient(135deg, var(--success) 0%, #45a049 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-next:hover,
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-back {
            background: #6A8042;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        #sleep_res {
            text-align: center;
            font-size: 0.9rem;
            margin: 8px 0;
            color: var(--success);
            font-weight: 700;
            padding: 8px;
            background: #e8f5e9;
            border-radius: 8px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Scrollbar Styling */
        .notes::-webkit-scrollbar {
            width: 8px;
        }

        .notes::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .notes::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .notes::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .notes {
                position: relative;
                max-height: none;
            }
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .step-dot {
            width: 10px;
            height: 10px;
            background: #ddd;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background: var(--primary);
            width: 30px;
            border-radius: 10px;
        }
    </style>

    <script>
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</head>
<body>
    <!-- Navbar -->
    <header>
        <div class="logo-image">
            <img src="Healthy.png" alt="Logo Healthy">
        </div>
        <nav>
            <ul class="nav-links"> <!-- PASTIKAN class di sini -->
                <li><a href="index.html">HOME</a></li>
                <li><a href="rekomendasi.html">REKOMENDASI</a></li>
                <li><a href="jadwal.php" class="active">JADWAL</a></li>
                <li><a href="logout.php" style="color: white;">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero-jadwal">
        <div class="hero-text">
            <h1><span class="orange">TRACKING HARIAN</span></h1>
            <h2><span class="green">Halo, <?php echo $_SESSION['nama']; ?>!</span></h2>
            <p>Catat dan pantau progres kesehatanmu hari ini, karena setiap catatan kecil adalah bukti bahwa kamu bergerak, dan ingin berubah </p>
        </div>
    </section>

    <section>
        <div class="dashboard-container">
            <!-- Main Content -->
            <main class="main-content">
                <!-- Header -->
                <header class="header">
                    <div class="greeting">
                        <h1>Selamat Hidup Sehat! 👋</h1>
                        <p><?php echo date('d F Y, l'); ?></p>
                    </div>
                </header>
            </main>
        </div>
    </section>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Schedule/History Section -->
        <div class="schedule">
            <h3>
                <span>📅</span>
                Riwayat Aktivitas
            </h3>
            <div id="scheduleList" class="schedule-list">
                <?php
                // Koneksi database dan session
                include 'koneksi.php';
                
                // Pastikan user sudah login
                if (!isset($_SESSION['user_id'])) {
                    echo '<div class="empty-state">Silakan login terlebih dahulu</div>';
                    exit;
                }
                
                $id_login = $_SESSION['user_id'];
                
                // Query untuk mengambil data daily_note
                $query = "SELECT * FROM daily_note WHERE user_id = '$id_login' ORDER BY note_date DESC, created_at DESC LIMIT 10";
                $result = mysqli_query($conn, $query);
                
                // Cek jika ada data
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $tgl_note = $row['note_date'];
                        $priorityClass = "bg-" . strtolower($row['priority']);
                        $note_id = $row['note_id'];
                ?>
                
                <div class="note-item">
                    <div class="note-header">
                        <div style="display: flex; justify-content: space-between; align-items:center;">
                            <div class="note-date">📅 <?php echo date('d F Y', strtotime($tgl_note)); ?></div>
                            <span class="badge <?php echo $priorityClass; ?>"><?php echo $row['priority']; ?></span>
                        </div>
                        <div class="note-title"><?php echo htmlspecialchars($row['title']); ?></div>
                        <div class="note-text"><?php echo nl2br(htmlspecialchars($row['description'])); ?></div>
                    </div>

                    <?php 
                    // Meal Log
                    $q_meal = mysqli_query($conn, "SELECT * FROM meal_log WHERE user_id='$id_login' AND meal_date='$tgl_note'");
                    if(mysqli_num_rows($q_meal) > 0){
                        echo '<div class="activity-box box-meal">
                                <div class="box-header">🍽️ Nutrisi & Kalori</div>';
                        while($m = mysqli_fetch_assoc($q_meal)){
                            echo "<div class='detail-row'>
                                    <div class='d-bullet'>•</div>
                                    <div class='d-content'>
                                        <strong>" . htmlspecialchars($m['food_name']) . "</strong> (" . $m['calories'] . " kkal)
                                        <div class='d-meta'>" . htmlspecialchars($m['meal_type']) . " | Jam: " . $m['meal_time'] . "</div>
                                        <div class='d-meta'><i>Note: " . htmlspecialchars($m['notes']) . "</i></div>
                                    </div>
                                  </div>";
                        }
                        echo '</div>';
                    }
                    
                    // Water Intake
                    $q_water = mysqli_query($conn, "SELECT * FROM water_intake WHERE user_id='$id_login' AND intake_date='$tgl_note'");
                    if(mysqli_num_rows($q_water) > 0){
                        echo '<div class="activity-box box-water">
                                <div class="box-header">💧 Hidrasi Tubuh</div>';
                        while($w = mysqli_fetch_assoc($q_water)){
                            $time_show = date('H:i', strtotime($w['intake_time']));
                            echo "<div class='detail-row'>
                                    <div class='d-bullet'>•</div>
                                    <div class='d-content'>
                                        Minum <strong>" . $w['amount_ml'] . " ml</strong>
                                        <div class='d-meta'>Pukul $time_show</div>
                                    </div>
                                  </div>";
                        }
                        echo '</div>';
                    }
                    
                    // Exercise Log
                    $q_ex = mysqli_query($conn, "SELECT * FROM exercise_log WHERE user_id='$id_login' AND exercise_date='$tgl_note'");
                    if(mysqli_num_rows($q_ex) > 0){
                        echo '<div class="activity-box box-exercise">
                                <div class="box-header">🏃 Aktivitas Fisik</div>';
                        while($ex = mysqli_fetch_assoc($q_ex)){
                            echo "<div class='detail-row'>
                                    <div class='d-bullet'>•</div>
                                    <div class='d-content'>
                                        <strong>" . htmlspecialchars($ex['exercise_type']) . "</strong>
                                        <div class='d-meta'>Durasi: " . $ex['duration_minutes'] . " menit | Intensitas: " . htmlspecialchars($ex['intensity']) . "</div>
                                    </div>
                                  </div>";
                        }
                        echo '</div>';
                    }
                    
                    // Sleep Log
                    $q_sleep = mysqli_query($conn, "SELECT * FROM sleep_log WHERE user_id='$id_login' AND sleep_date='$tgl_note'");
                    if(mysqli_num_rows($q_sleep) > 0){
                        echo '<div class="activity-box box-sleep">
                                <div class="box-header">😴 Istirahat Malam</div>';
                        while($sl = mysqli_fetch_assoc($q_sleep)){
                            $durasi = number_format($sl['duration_hours'], 1);
                            echo "<div class='detail-row'>
                                    <div class='d-content'>
                                        Tidur: <strong>$durasi Jam</strong>
                                        <div class='d-meta'>" . $sl['bedtime'] . " - " . $sl['wake_time'] . "</div>
                                        <div class='d-meta'>Kualitas: " . $sl['quality_rating'] . "/10 (" . htmlspecialchars($sl['notes']) . ")</div>
                                    </div>
                                  </div>";
                        }
                        echo '</div>';
                    }
                    
                    // Health Goal
                    $q_goal = mysqli_query($conn, "SELECT * FROM health_goal WHERE user_id='$id_login' AND start_date='$tgl_note'");
                    if(mysqli_num_rows($q_goal) > 0){
                        echo '<div class="activity-box box-goal">
                                <div class="box-header">🎯 Target Baru Ditetapkan</div>';
                        while($g = mysqli_fetch_assoc($q_goal)){
                            echo "<div class='detail-row'>
                                    <div class='d-content'>
                                        <strong>" . htmlspecialchars($g['goal_title']) . "</strong> <span style='font-size:0.8rem; border:1px solid red; padding:0 4px; border-radius:3px;'>" . htmlspecialchars($g['goal_type']) . "</span>
                                        <div class='d-meta'>Target: " . $g['target_value'] . " | Deadline: " . $g['target_date'] . "</div>
                                    </div>
                                  </div>";
                        }
                        echo '</div>';
                    }
                    ?>
                    
                    <a href="jadwal.php?hapus=<?php echo $note_id; ?>" class="delete-btn" onclick="return confirm('Yakin hapus data tanggal ini?')">🗑️ Hapus Laporan</a>
                </div>
                
                <?php
                    } // end while
                } else {
                    echo '<div class="empty-state">
                            <div class="empty-state-icon">📝</div>
                            <p><strong>Belum ada data aktivitas</strong></p>
                            <p style="font-size: 0.9rem; margin-top: 10px;">Mulai catat aktivitas kesehatanmu hari ini!</p>
                          </div>';
                }
                ?>
            </div>
        </div>

        <!-- Form Input Section -->
        <div class="notes">
            <h2>📝 INPUT DATA BARU</h2>
            <p>Catat aktivitas kesehatanmu hari ini</p>
            
            <div class="step-indicator">
                <div class="step-dot active" id="dot1"></div>
                <div class="step-dot" id="dot2"></div>
                <div class="step-dot" id="dot3"></div>
                <div class="step-dot" id="dot4"></div>
            </div>
            
            <form action="" method="POST" id="mainForm">
                <!-- Step 1: Catatan Utama -->
                <div id="step1" class="form-section">
                    <p style="font-weight: 600; margin-bottom: 15px; color: #6A8042;">
                        <strong>Langkah 1/4:</strong> Catatan Utama
                    </p>
                    <input type="text" id="title" name="title" placeholder="📌 Judul Catatan (Cth: Lari Pagi)" required>
                    <input type="date" id="note_date" name="note_date" required>
                    <select name="category" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Kesehatan">🏥 Kesehatan</option>
                        <option value="Olahraga">🏃 Olahraga</option>
                        <option value="Nutrisi">🍎 Nutrisi</option>
                        <option value="Personal">👤 Personal</option>
                    </select>
                    <select name="priority" required>
                        <option value="">-- Pilih Prioritas --</option>
                        <option value="Rendah">🟢 Prioritas Rendah</option>
                        <option value="Sedang">🟡 Prioritas Sedang</option>
                        <option value="Tinggi">🔴 Prioritas Tinggi</option>
                    </select>
                    <textarea id="description" name="description" placeholder="✍️ Deskripsi singkat hari ini..." required></textarea>
                    
                    <div class="btn-group">
                        <button type="button" class="btn-next" onclick="showStep(2)">BERIKUTNYA →</button>
                    </div>
                </div>

                <!-- Step 2: Nutrisi -->
                <div id="step2" class="form-section">
                    <p style="font-weight: 600; margin-bottom: 15px; color: #6A8042;">
                        <strong>Langkah 2/4:</strong> Nutrisi & Hidrasi
                    </p>
                    
                    <div class="subtitle-form">🍽️ Makanan</div>
                    <select name="meal_type">
                        <option value="">-- Tipe Makan --</option>
                        <option value="Sarapan">🌅 Sarapan</option>
                        <option value="Makan Siang">☀️ Makan Siang</option>
                        <option value="Makan Malam">🌙 Makan Malam</option>
                        <option value="Snack">🍪 Snack</option>
                    </select>
                    <input type="time" name="meal_time" placeholder="Jam Makan">
                    <input type="text" name="food_name" placeholder="🍕 Nama Makanan">
                    <input type="number" name="calories" placeholder="🔥 Kalori (kkal)" min="0">
                    <textarea name="meal_notes" placeholder="📝 Catatan makanan..." rows="2"></textarea>

                    <div class="subtitle-form">💧 Air Minum</div>
                    <div style="display:flex; gap:8px;">
                        <input type="time" name="intake_time" style="flex:1;" placeholder="Jam">
                        <input type="number" name="amount_ml" placeholder="💧 Jumlah (ml)" style="flex:1;" min="0">
                    </div>
                    
                    <div class="btn-group">
                        <button type="button" class="btn-back" onclick="showStep(1)">← KEMBALI</button>
                        <button type="button" class="btn-next" onclick="showStep(3)">BERIKUTNYA →</button>
                    </div>
                </div>

                <!-- Step 3: Fisik & Istirahat -->
                <div id="step3" class="form-section">
                    <p style="font-weight: 600; margin-bottom: 15px; color: #6A8042;">
                        <strong>Langkah 3/4:</strong> Fisik & Istirahat
                    </p>
                    
                    <div class="subtitle-form">🏃 Olahraga</div>
                    <input type="text" name="exercise_type" placeholder="🎯 Jenis Olahraga (Cth: Jogging)">
                    <div style="display:flex; gap:8px;">
                        <input type="time" name="start_time" style="flex:1;" placeholder="Mulai">
                        <input type="number" name="duration_minutes" placeholder="⏱️ Menit" style="flex:1;" min="0">
                    </div>
                    <select name="intensity">
                        <option value="">-- Intensitas --</option>
                        <option value="Ringan">🟢 Ringan</option>
                        <option value="Sedang">🟡 Sedang</option>
                        <option value="Berat">🔴 Berat</option>
                    </select>

                    <div class="subtitle-form">😴 Tidur</div>
                    <div style="display:flex; gap:8px;">
                        <div style="flex:1;">
                            <span class="small-label">🌙 Tidur</span>
                            <input type="time" id="bedtime" name="bedtime" onchange="calcSleep()">
                        </div>
                        <div style="flex:1;">
                            <span class="small-label">🌅 Bangun</span>
                            <input type="time" id="wake_time" name="wake_time" onchange="calcSleep()">
                        </div>
                    </div>
                    <div id="sleep_res">-</div>
                    <input type="number" name="quality_rating" min="1" max="10" placeholder="⭐ Rating Kualitas (1-10)">
                    <textarea name="sleep_notes" placeholder="📝 Catatan tidur..." rows="2"></textarea>
                    
                    <div class="btn-group">
                        <button type="button" class="btn-back" onclick="showStep(2)">← KEMBALI</button>
                        <button type="button" class="btn-next" onclick="showStep(4)">BERIKUTNYA →</button>
                    </div>
                </div>

                <!-- Step 4: Target -->
                <div id="step4" class="form-section">
                    <p style="font-weight: 600; margin-bottom: 15px; color: #6A8042;">
                        <strong>Langkah 4/4:</strong> Target (Opsional)
                    </p>
                    
                    <div class="subtitle-form">🎯 Health Goal</div>
                    <select name="goal_type">
                        <option value="">-- Jenis Target --</option>
                        <option value="Berat Badan">⚖️ Berat Badan</option>
                        <option value="Olahraga">🏃 Olahraga</option>
                        <option value="Nutrisi">🥗 Nutrisi</option>
                        <option value="Tidur">😴 Tidur</option>
                    </select>
                    <input type="text" name="goal_title" placeholder="📌 Judul Target">
                    <textarea name="goal_desc" placeholder="📝 Deskripsi target..." rows="2"></textarea>
                    <div style="display:flex; gap:8px;">
                        <input type="number" step="0.01" name="target_value" placeholder="🎯 Target Angka" style="flex:1;">
                        <input type="number" step="0.01" name="current_value" placeholder="📊 Angka Saat Ini" style="flex:1;">
                    </div>
                    <div style="display:flex; gap:8px;">
                        <div style="flex:1;">
                            <span class="small-label">📅 Mulai</span>
                            <input type="date" name="start_date">
                        </div>
                        <div style="flex:1;">
                            <span class="small-label">⏰ Deadline</span>
                            <input type="date" name="target_date">
                        </div>
                    </div>
                    <select name="goal_status">
                        <option value="Aktif">✅ Aktif</option>
                        <option value="Selesai">🏁 Selesai</option>
                    </select>
                    
                    <div class="btn-group">
                        <button type="button" class="btn-back" onclick="showStep(3)">← KEMBALI</button>
                        <button type="submit" name="simpan_semua" class="btn-submit">💾 SIMPAN SEMUA</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showStep(stepNumber) {
            // Validasi step 1
            if(stepNumber > 1) {
                var title = document.getElementById('title').value;
                var date = document.getElementById('note_date').value;
                if(title == "" || date == "") {
                    alert("⚠️ Judul & Tanggal wajib diisi!");
                    return;
                }
            }
            
            // Hide all steps
            for(let i = 1; i <= 4; i++) {
                document.getElementById('step' + i).style.display = 'none';
                document.getElementById('dot' + i).classList.remove('active');
            }
            
            // Show current step
            document.getElementById('step' + stepNumber).style.display = 'block';
            document.getElementById('dot' + stepNumber).classList.add('active');
            
            // Smooth scroll to top of form
            document.querySelector('.notes').scrollTop = 0;
        }

        function calcSleep() {
            var bedtime = document.getElementById("bedtime").value;
            var waketime = document.getElementById("wake_time").value;
            
            if (bedtime && waketime) {
                var d1 = new Date("2000-01-01 " + bedtime);
                var d2 = new Date("2000-01-01 " + waketime);
                
                // Jika waktu bangun lebih awal dari tidur, berarti melewati tengah malam
                if (d2 < d1) {
                    d2.setDate(d2.getDate() + 1);
                }
                
                var diff = (d2 - d1) / 1000 / 60 / 60; // Convert to hours
                var hours = Math.floor(diff);
                var minutes = Math.round((diff - hours) * 60);
                
                document.getElementById("sleep_res").innerText = 
                    "💤 Durasi: " + hours + " jam " + minutes + " menit";
            }
        }

        // Set default date to today
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementById('note_date').value = today;
        });
    </script>

    <div class="main-cursor"></div>

    <script>
        const mainCursor = document.querySelector(".main-cursor");

        window.addEventListener("mousemove", function (e) {
            const posX = e.clientX;
            const posY = e.clientY;

            // 1. Gerakkan kursor utama (gambar besar) secara instan
            if (mainCursor) {
                mainCursor.style.left = `${posX}px`;
                mainCursor.style.top = `${posY}px`;
            }

            // 2. Panggil fungsi untuk membuat partikel (gambar kecil)
            createParticle(posX, posY);
        });

        // Fungsi untuk menciptakan elemen gambar jejak
        function createParticle(x, y) {
            const particle = document.createElement('div');
            particle.classList.add('cursor-particle');
            document.body.appendChild(particle);

            // Set posisi awal partikel tepat di lokasi mouse saat ini
            particle.style.left = `${x}px`;
            particle.style.top = `${y}px`;

            // Tambahkan sedikit variasi rotasi acak agar terlihat alami
            const randomRotation = Math.random() * 360;
            // Kita set transform awal di sini agar bisa ditimpa oleh animasi CSS
            particle.style.setProperty('--rotation', `${randomRotation}deg`);

            // Hapus elemen partikel setelah 1 detik (sesuai durasi animasi di CSS)
            // agar website tidak menjadi berat.
            setTimeout(() => {
                particle.remove();
            }, 1000);
        }
    </script>
</body>
</html>