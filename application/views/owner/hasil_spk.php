<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Halaman Hasil SPK</h1>

  <!-- Tabel Kriteria -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tabel Hasil SPK</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <?= $this->session->flashdata('pesan') ?>
        <!--<a target="blank" href="<?= base_url('Alternatif/exportpdf'); ?>" class="btn btn-danger mb-3"><i class="fa fa-print"></i> Export ke PDF </a>-->
        <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr align="center">
              <th>No.</th>
              <th>Nama Alternatif</th>
              <th>Nilai Preferensi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            <?php foreach ($alternatif as $alt) : ?>
              <tr>
                <td align="center" scope="row"><?= $i; ?></td>
                <td><?= $alt->nama_alternatif ?></td>
                <td align="center"><?= $alt->nilai_preferensi ?></td>
              </tr>
              <?php $i++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>