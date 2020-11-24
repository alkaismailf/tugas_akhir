<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Halaman Hasil Perhitungan Aturan Asosiasi</h1>

  <!-- Tabel Kriteria -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tabel Hasil Aturan</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <?= $this->session->flashdata('pesan') ?>
        <?php foreach ($process_log as $log) : ?>
          <label> Minimum Support     : <?= $log->min_support ?> % </label><br>
          <label> Minimum Confidence  : <?= $log->min_confidence ?> % </label>
        <?php endforeach; ?>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr align="center">
              <th>No.</th>
              <th>Kombinasi Item</th>
              <th>Confidence</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            <?php foreach ($confidence as $conf) : ?>
              <tr>
                <td align="center" scope="row"><?= $i; ?></td>
                <td><?= $conf->kombinasi1 ?> --> <?= $conf->kombinasi2 ?></td>
                <td align="center"><?= number_format($conf->confidence,2, ',', '.') ?></td>
              </tr>
              <?php $i++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>