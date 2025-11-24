<?php
include '../koneksi.php';

// Ambil data dari form
$meeting_id = $_POST['meeting_id'];
$participant_id = $_POST['participant_id'];

// Generate token unik untuk link undangan
$token = bin2hex(random_bytes(16)); // 32 karakter acak

// Query INSERT sesuai struktur tabel meetings_participant
$sql = "INSERT INTO meetings_participant 
        (meeting_id, participant_id, attendance_status, invite_token)
        VALUES ('$meeting_id', '$participant_id', 'pending', '$token')";

if (mysqli_query($koneksi, $sql)) {

    // Link untuk menerima undangan
    $accept_link = "http://localhost/meeting/accept_invite.php?token=$token";

    echo "<script>
            alert('Undangan berhasil dikirim!');
            window.location='undangan_admin.php?notif=sent';
          </script>";
} else {
    echo "<script>
            alert('Gagal mengirim undangan: " . mysqli_error($koneksi) . "');
            window.location='undangan_admin.php';
          </script>";
}
?>
