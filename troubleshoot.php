<!DOCTYPE html>
<html lang="id">
<head>
    <title>SPK Troubleshooting Jaringan</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 20px; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; }
        h2 { color: #333; }
        .result { margin-top: 20px; padding: 15px; border-left: 5px solid #2196F3; background: #e3f2fd; }
        button { background: #2196F3; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; }
        button:hover { background: #1976D2; }
    </style>
</head>
<body>

<div class="container">
    <h2>Analisis Kerusakan Jaringan (Backward Chaining)</h2>
    <p>Pilih gejala yang terdeteksi di kantor:</p>
    
    <form method="POST">
        <input type="checkbox" name="gejala[]" value="lampu_indikator_mati"> Lampu indikator modem mati<br>
        <input type="checkbox" name="gejala[]" value="ikon_jaringan_silang"> Ikon jaringan di komputer silang (X)<br>
        <input type="checkbox" name="gejala[]" value="ping_gateway_sukses"> Bisa Ping ke Gateway/Router (192.168.1.1)<br>
        <input type="checkbox" name="gejala[]" value="ping_google_gagal"> Gagal Ping ke Google (8.8.8.8)<br>
        <input type="checkbox" name="gejala[]" value="ping_ip_sukses"> Bisa Ping ke IP (8.8.8.8) tapi tidak bisa buka website<br>
        <br>
        <button type="submit" name="proses">Mulai Analisis</button>
    </form>

    <?php
    if (isset($_POST['proses'])) {
        // 1. Ambil input dari user dan masukkan ke array facts
        $input_gejala = isset($_POST['gejala']) ? $_POST['gejala'] : [];
        $facts = [];
        foreach ($input_gejala as $g) {
            $facts[$g] = true;
        }

        // 2. Knowledge Base (Aturan)
        $rules = [
            'Kabel LAN Putus / Hardware Rusak' => ['lampu_indikator_mati', 'ikon_jaringan_silang'],
            'Gangguan Layanan ISP' => ['ping_gateway_sukses', 'ping_google_gagal'],
            'Konfigurasi DNS Salah' => ['ping_ip_sukses']
        ];

        echo "<div class='result'>";
        echo "<h3>Hasil Diagnosa:</h3>";

        $ditemukan = false;
        
        // 3. Mesin Inferensi Backward Chaining (Mengecek setiap Goal)
        foreach ($rules as $goal => $conditions) {
            $allConditionsMet = true;
            foreach ($conditions as $condition) {
                if (!isset($facts[$condition])) {
                    $allConditionsMet = false;
                    break;
                }
            }

            if ($allConditionsMet) {
                echo "✅ Kemungkinan Besar: <strong>$goal</strong><br>";
                $ditemukan = true;
            }
        }

        if (!$ditemukan) {
            echo "❌ Gejala tidak mencukupi untuk menentukan kerusakan secara pasti.";
        }
        echo "</div>";
    }
    ?>
</div>

</body>
</html>