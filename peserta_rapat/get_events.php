<?php
// peserta/get_events.php
header('Content-Type: application/json');
session_start();

include '../koneksi.php';

// Cek user login
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Ambil participant_id dari tabel users
$qUser = mysqli_query($koneksi, "SELECT participant_id FROM users WHERE id = $user_id LIMIT 1");
$userData = mysqli_fetch_assoc($qUser);

if (!$userData) {
    echo json_encode([]);
    exit();
}

$participant_id = $userData['participant_id'];

// Pastikan participant_id valid
if (!$participant_id) {
    echo json_encode([]);
    exit();
}

// Query: Ambil rapat yang diikuti peserta dari meetings + meetings_participant
$sql = "
    SELECT 
        m.id,
        m.title,
        m.descriptions,
        m.dates,
        m.start_time,
        m.end_time,
        m.locations,
        m.leader,
        m.status_meetings
    FROM meetings m
    INNER JOIN meetings_participant mp ON mp.meeting_id = m.id
    WHERE mp.participant_id = $participant_id
    ORDER BY m.dates ASC, m.start_time ASC
";

$result = mysqli_query($koneksi, $sql);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    // Format start datetime
    $start = $row['dates'];
    if (!empty($row['start_time']) && $row['start_time'] !== "00:00:00") {
        $start .= "T" . $row['start_time'];
    }

    // Format end datetime
    $end = null;
    if (!empty($row['end_time']) && $row['end_time'] !== "00:00:00") {
        $end = $row['dates'] . "T" . $row['end_time'];
    }

    $events[] = [
        "id" => intval($row['id']),
        "title" => $row['title'],
        "start" => $start,
        "end" => $end,
        "extendedProps" => [
            "description" => $row['descriptions'],
            "location" => $row['locations'],
            "leader" => $row['leader'],
            "status" => $row['status_meetings']
        ]
    ];
}

// Return JSON
echo json_encode($events);
