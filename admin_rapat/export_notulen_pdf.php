<?php
require '../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

include '../koneksi.php';

// Ambil ID notulen
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query($koneksi, "SELECT * FROM minutes WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data notulen tidak ditemukan.");
}

// HTML PDF
$html = '
<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12pt;
    padding: 20px;
}
h1 {
    text-align: center;
    border-bottom: 2px solid #333;
    padding-bottom: 10px;
}
table {
    width: 100%;
    margin-bottom: 20px;
}
td {
    padding: 6px;
    vertical-align: top;
}
.label {
    width: 180px;
    font-weight: bold;
}
.section-title {
    font-size: 14pt;
    margin-top: 20px;
    margin-bottom: 10px;
    font-weight: bold;
}
.box {
    border: 1px solid #aaa;
    padding: 10px;
    background: #f9f9f9;
    border-radius: 6px;
}
</style>

<h1>Notulen Rapat</h1>

<table>
<tr><td class="label">Judul</td><td>: '.$data['title'].'</td></tr>
<tr><td class="label">Agenda</td><td>: '.$data['agenda'].'</td></tr>
<tr><td class="label">Pembuat</td><td>: '.$data['created_by'].'</td></tr>
<tr><td class="label">Tanggal</td><td>: '.date('d M Y', strtotime($data['created_at'])).'</td></tr>
</table>

<div class="section-title">Pembahasan</div>
<div class="box">'.$data['notes'].'</div>

<div class="section-title">Keputusan</div>
<div class="box">'.$data['decisions'].'</div>

<div class="section-title">Tindak Lanjut</div>
<div class="box">'.$data['follow_up'].'</div>
';

// Generate PDF
$pdf = new Html2Pdf('P','A4','en');
$pdf->writeHTML($html);
$pdf->output('notulen_'.$data['id'].'.pdf');
