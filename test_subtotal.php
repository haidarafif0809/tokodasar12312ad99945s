
<table border="1">
<thead>
<th>
Nomor 
</th>
<th>
No Faktur 
</th>
<th>
Total Penjualan
</th>
<th>
Total Detail Penjualan
</th>
<th>
Selisih
</th>
<th>
Tanggal
</th>
<th>
Jam
</th>
</thead>

<tbody>

<?php 
include 'db.php';

$nomor = 0;
$total_selisih = 0;
$query = $db->query("SELECT penjualan.no_faktur ,penjualan.tanggal,penjualan.jam, penjualan.total, SUM(detail_penjualan.subtotal) AS total_detail, penjualan.total - SUM(detail_penjualan.subtotal) AS selisih FROM penjualan  LEFT JOIN detail_penjualan  ON penjualan.no_faktur = detail_penjualan.no_faktur GROUP BY detail_penjualan.no_faktur  ORDER BY penjualan.tanggal DESC");

while($data = $query->fetch_array()){

if($data['selisih'] != 0) {
$nomor = $nomor + 1;
$total_selisih = $total_selisih + $data['selisih'];
echo "<tr><td>".$nomor."</td><td>".$data['no_faktur']."</td><td>".$data['total']."</td><td>".$data['total_detail']."</td><td>".$data['selisih']."</td><td>".$data['tanggal']."</td><td>".$data['jam']."</td></tr>";
}

}

echo "<tr><td></td><td></td><td></td><td></td>Total Selisih<td>".$total_selisih."</td><td></td><td></td></tr>";
?>

</tbody>
</table>