<!DOCTYPE html>
<html>
<head>
  <title>Hasil Pengambilan Keputusan</title>
  <style type="text/css">
    #outtable{
      padding: 20px;
      border:1px solid #e3e3e3;
      width:660px;
      border-radius: 5px;
    }
 
    .short{
      width: 50px;
    }
 
    .normal{
      width: 150px;
    }
 
    table{
      border-collapse: collapse;
      font-family: Times new roman;
    }
 
    thead th{
      text-align: center;
      padding: 10px;
    }
 
    tbody td{
      border-top: 1px solid #e3e3e3;
      padding: 10px;
    }
 
    tbody tr:nth-child(even){
      background: #F6F5FA;
    }
 
    tbody tr:hover{
      background: #EAE9F5
    }
  </style>
</head>
<body>
  <div id="table1">
    <img src="assets/img/logo_depdoo.jpg" style="position: absolute; width: 100px; height: auto">
    <table width="100%" cellspacing="0">
      <tr>
        <td align="center">
          <span style="line-height: 1.6; font-weight: bold;">
            DEPDOO
            <br>Depot Pedoo
          </span>
        </td>
      </tr>
    </table>
  </div>
    <br>
    <hr class="line-title">
    <br>
    <p style="line-height: 1.6; font-weight: bold; text-align: center;" >
      LAPORAN HASIL PENGAMBILAN KEPUTUSAN <br>
      <b>Oktober 2019</b>
    </p>

	<div id="outtable">
	  <table width="100%" cellspacing="0">
	  	<thead>
	  		<tr>
	  			<th class="short">No.</th>
	  			<th class="normal">Nama Alternatif</th>
	  			<th class="normal">Nilai Preferensi</th>
	  		</tr>
	  	</thead>
	  	<tbody>
	  		<?php $no=1; ?>
	  		<?php foreach ($alternatif as $alt) : ?>
	  		<tr>
	  			<td align="center"><?php echo $no; ?></td>
	  			<td><?php echo $alt->nama_alternatif; ?></td>
	  			<td align="center"><?= number_format($alt->nilai_preferensi,4, ',', '.') ?></td>
	  		</tr>
	  		<?php $no++; ?>
	  		<?php endforeach; ?>
	  	</tbody>
	  </table>
	 </div>
</body>
</html>