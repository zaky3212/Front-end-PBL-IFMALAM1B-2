<?php
require '../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
include '../koneksi.php';

// ambil ID
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM minutes WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

// HTML untuk PDF dengan desain rapi
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
        margin-bottom: 20px;
        font-size: 20pt;
    }
    table {
        width: 100%;
        margin-bottom: 25px;
        border-collapse: collapse;
    }
    td {
        padding: 8px;
        vertical-align: top;
    }
    .label {
        width: 180px;
        font-weight: bold;
    }
    .section-title {
        font-size: 14pt;
        margin-top: 25px;
        margin-bottom: 10px;
        font-weight: bold;
        color: #222;
    }
    .box {
        border: 1px solid #aaa;
        padding: 10px;
        border-radius: 6px;
        background: #f9f9f9;
    }
</style>

<h1>Notulen Rapat</h1>

<table>
<tr>
<td class="label">Judul Rapat</td>
<td>: '.$data['title'].'</td>
</tr>
<tr>
<td class="label">Pembuat</td>
<td>: '.$data['created_by'].'</td>
</tr>
<tr>
<td class="label">Agenda</td>
<td>: '.$data['agenda'].'</td>
</tr>
<tr>
<td class="label">Tanggal Dibuat</td>
<td>: '.date("d M Y", strtotime($data['created_at'])).'</td>
</tr>
</table>

<div class="section-title">Pembahasan</div>
<div class="box">'.$data['notes'].'</div>

<div class="section-title">Keputusan</div>
<div class="box">'.$data['decisions'].'</div>

<div class="section-title">Tindak Lanjut</div>
<div class="box">'.$data['follow_up'].'</div>
';

// Generate PDF
$html2pdf = new Html2Pdf('P','A4','en');
$html2pdf->writeHTML($html);
$html2pdf->output("notulen_".$data['id'].".pdf");
?>
