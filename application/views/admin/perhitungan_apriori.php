<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Halaman Perhitungan Aturan Asosiasi</h1>
  <?= $this->session->flashdata('pesan') ?>
  <!-- Kolom Perhitungan -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Process Input</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <form method="post" action="<?= base_url().'Apriori/importexcel' ?>" enctype="multipart/form-data">
          <div class="form-group">
            <h6 style="height:25px;" class="m-0 font-weight-bold text-dark">Import Data Transaksi dari Excel (.csv) :</h6>
            <input type="file" name="excel" class="form-control" required accept=".csv">
          </div>
          <div class="modal-footer">
            <button type="submit" name="import" class="btn btn-success"><i class="fas fa-file-import fa-sm"></i> Import</button>
          </div>
        </form>
        <form method="post" action="<?= base_url().'Apriori/hitung_apriori' ?>">
          <input type="hidden" name="id_process" id="id_process">
          <div class="form-group">
            <h6 style="height:25px;" class="m-0 font-weight-bold text-dark">Minimum Support (%) :</h6>
            <input type="text" name="min_support" class="form-control" id="min_support" placeholder="Isi dengan angka antara 1 - 100" required>
          </div>
          <div class="form-group">
            <h6 style="height:25px;" class="m-0 font-weight-bold text-dark">Minimum Confidence (%) :</h6>
            <input type="text" name="min_confidence" class="form-control" id="min_confidence" placeholder="Isi dengan angka antara 1 - 100 (disarankan puluhan)" required>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-warning"><i class="far fa-arrow-alt-circle-right fa-sm"></i> Lakukan Perhitungan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Tabel Kriteria -->
  <div class="card shadow mb-4">
    <a href="#collapse1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse1">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Transaksi</h6>
    </a>
    <div class="collapse show" id="collapse1">
      <div class="card-body">
        <div class="table-responsive">
          <a href="<?= base_url('Apriori/hapusalltransaksi'); ?>" class="btn btn-danger mb-3" onclick="javascript: return confirm('Hapus semua data?')"><i class="fas fa-trash-alt fa-sm"></i> Hapus Semua Data Transaksi </a>
          <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr align="center">
                <th>No.</th>
                <th>Tanggal Transaksi</th>
                <th>Produk yang dibeli</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php foreach ($transaksi as $trs) : ?>
                <tr>
                  <td align="center" scope="row"><?= $i; ?></td>
                  <td><?= $trs->tgl_transaksi ?></td>
                  <td><?= $trs->produk ?></td>
                </tr>
                <?php $i++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!--
  <script>
    $(document).ready(function() {
      $('#dataTransaksi').DataTable();
    });
  </script>
  -->