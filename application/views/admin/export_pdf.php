<!DOCTYPE html>
<html><head>
	<title>Print Hasil Keputusan</title>
</head><body>

	<h3 align="center">Ranking Kombinasi Produk</h3>

	<table>
		<tr>
			<th>No.</th>
			<th>Kombinasi Produk</th>
			<th>Nilai Preferensi</th>
		</tr>

		<?php 
		$i = 1;
		foreach ($alternatif as $alt) : ?>

		<tr>
			<td><?= $i ?></td>
			<td><?= $mhs->nama_alternatif ?></td>
			<td><?= $mhs->nilai_preferensi ?></td>
		</tr>
		<?php $i++; 
		endforeach; ?>
	</table>
 
</body></html>