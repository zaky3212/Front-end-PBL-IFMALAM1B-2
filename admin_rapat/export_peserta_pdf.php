<?php
require '../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

include '../koneksi.php';

// Ambil data peserta
$query = mysqli_query($koneksi, "SELECT * FROM participant ORDER BY name ASC");

// HTML untuk PDF
$html = '
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11pt;
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
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        border: 1px solid #999;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: center;
    }
    .footer {
        margin-top: 30px;
        font-size: 10pt;
        text-align: right;
        color: #555;
    }
</style>

<h1>Daftar Peserta Rapat</h1>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peserta</th>
            <th>Email</th>
            <th>Departemen</th>
            <th>Jabatan</th>
            <th>Telepon</th>
        </tr>
    </thead>
    <tbody>
';

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $html .= '
        <tr>
            <td style="text-align:center;">'.$no++.'</td>
            <td>'.htmlspecialchars($row['name']).'</td>
            <td>'.htmlspecialchars($row['email']).'</td>
            <td>'.htmlspecialchars($row['department']).'</td>
            <td>'.htmlspecialchars($row['position']).'</td>
            <td>'.htmlspecialchars($row['phone']).'</td>
        </tr>
    ';
}

$html .= '
    </tbody>
</table>

<div class="footer">
    Dicetak pada: '.date("d M Y H:i").'
</div>
';

// Generate PDF
$html2pdf = new Html2Pdf('P', 'A4', 'en');
$html2pdf->writeHTML($html);
$html2pdf->output("daftar_peserta_rapat.pdf");
?>
