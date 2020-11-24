<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Halaman Kriteria</h1>

  <!-- Tabel Kriteria -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tabel Kriteria</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <?= $this->session->flashdata('pesan') ?>
        <!--<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
        <?= form_error('namakriteria', '<div class="alert alert-danger pl-3" role="alert">', '</div>'); ?>
        <?= form_error('atribut', '<div class="alert alert-danger pl-3" role="alert">', '</div>'); ?>
        <?= form_error('bobot', '<div class="alert alert-danger pl-3" role="alert">', '</div>'); ?>
        <?= form_error('nama', '<div class="alert alert-danger pl-3" role="alert">', '</div>'); ?>-->
        <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newkritModal"><i class="fas fa-plus-circle fa-sm"></i> Tambah Kriteria </a>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr align="center">
              <th>No.</th>
              <th>Nama Kriteria</th>
              <th>Tipe Kriteria</th>
              <th>Bobot</th>
              <th colspan="3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            <?php foreach ($kriteria as $kri) : ?>
              <tr>
                <td align="center" scope="row"><?= $i; ?></td>
                <td><?= $kri->nama_kriteria ?></td>
                <td><?php
                      if ($kri->tipe_kriteria == '1') {
                        echo (" Cost / Biaya");
                      } else {
                        echo (" Benefit / Keuntungan");
                      } ?></td>
                <td align="center"><?= number_format($kri->bobot,1, '.', '.') ?></td>
                <td align="center">
                  <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#neweditModal<?= $kri->id_kriteria ?>">
                    <span class="icon text-white-0">
                      <i class="far fa-edit"></i>
                    </span>
                  </a>
                </td>
                <td align="center" onclick="javascript: return confirm('Hapus data?')">
                  <?= anchor('kriteria/hapusKriteria/'.$kri->id_kriteria, '<div class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></div>') ?>
                </td>
              </tr>
              <?php $i++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

<!-- Modal tambah data Kriteria -->
<div class="modal fade" id="newkritModal" tabindex="-1" role="dialog" aria-labelledby="newkritModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newkritModalLabel">Tambah Kriteria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('kriteria/tambahKriteria'); ?>" method="post">
        <div class="modal-body">
          <!-- <input type="hidden" name="id_kriteria" value="<?= $kode ?>" readonly> -->
          <div class="form-group">
            <label>Nama Kriteria </label>
            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" placeholder="Nama Kriteria" required>
          </div>
          <label>Tipe Kriteria </label>
          <div class="input-group mb-3">
            <select id="tipe_kriteria" name="tipe_kriteria" class="form-control">
              <option value="">- Pilih Atribut -</option>
              <option value="0" <?php if ($kri->tipe_kriteria == 0) {
                                    echo 'selected';
                                  } ?>> Benefit/Keuntungan </option>
              <option value="1" <?php if ($kri->tipe_kriteria == 1) {
                                    echo 'selected';
                                  }  ?>> Cost/Biaya </option>
            </select>
          </div>
          <div class="form-group">
            <label class="col-form-label ml-1 mr-4 ">Bobot Kriteria </label>
            <input type="text" class="form-control" id="bobot" name="bobot" placeholder="angka desimal max berjumlah 1 jika ditotal keseluruhan"  required>
            <!--<div class="form-check form-check-inline mr-3 ">
              <input class="form-check-input" type="radio" name="bobot" id="bobot" value="1">
              <label class="form-check-label" for="inlineRadio1">1</label>
            </div>
            <div class="form-check form-check-inline mr-3">
              <input class="form-check-input" type="radio" name="bobot" id="bobot" value="2">
              <label class="form-check-label" for="inlineRadio2">2</label>
            </div>
            <div class="form-check form-check-inline mr-3 ">
              <input class="form-check-input" type="radio" name="bobot" id="bobot" value="3">
              <label class="form-check-label" for="inlineRadio3">3 </label>
            </div>
            <div class="form-check form-check-inline mr-3">
              <input class="form-check-input" type="radio" name="bobot" id="bobot" value="4">
              <label class="form-check-label" for="inlineRadio3">4 </label>
            </div>
            <div class="form-check form-check-inline mr-3">
              <input class="form-check-input" type="radio" name="bobot" id="bobot" value="5">
              <label class="form-check-label" for="inlineRadio3">5 </label>
            </div>-->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal ubah data kriteria-->
<?php
foreach ($kriteria as $kri) :
  ?>
  <div class="modal fade" id="neweditModal<?= $kri->id_kriteria ?>" tabindex="-1" role="dialog" aria-labelledby="neweditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="neweditModalLabel">Ubah Kriteria</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?= base_url('kriteria/ubahKriteria'); ?>" method="post">
          <div class="modal-body">
            <input type="hidden" name="id_kriteria" value="<?= $kri->id_kriteria ?>">
            <div class="form-group">
              <label>Nama Kriteria </label>
              <input type="text" class="form-control" id="nama_kriteria" name="nama kriteria" value="<?= $kri->nama_kriteria ?>">
            </div>
            <label>Tipe Kriteria </label>
            <div class="input-group mb-3">
              <select id="tipe_kriteria" name="tipe_kriteria" class="form-control">
                <option value="0" <?php if ($kri->tipe_kriteria == 0) {
                                      echo 'selected';
                                    } ?>> Benefit/Keuntungan </option>
                <option value="1" <?php if ($kri->tipe_kriteria == 1) {
                                      echo 'selected';
                                    }  ?>> Cost/Biaya </option>
              </select>
            </div>
            <div class="form-group">
              <label class="col-form-label ml-1 mr-4 ">Bobot Kriteria </label>
              <input type="text" class="form-control" id="bobot" name="bobot" value="<?= $kri->bobot ?>">
              <!-- <div class="form-check form-check-inline mr-3 ">
                <input class="form-check-input" type="radio" name="bobot" id="bobot" value="1" <?php if ($kri->bobot == 1) {
                                                                                                    echo 'checked';
                                                                                                  } ?>>
                <label class="form-check-label" for="inlineRadio1">1</label>
              </div>
              <div class="form-check form-check-inline mr-3">
                <input class="form-check-input" type="radio" name="bobot" id="bobot" value="2" <?php if ($kri->bobot == 2) {
                                                                                                    echo 'checked';
                                                                                                  } ?>>
                <label class="form-check-label" for="inlineRadio2">2</label>
              </div>
              <div class="form-check form-check-inline mr-3 ">
                <input class="form-check-input" type="radio" name="bobot" id="bobot" value="3" <?php if ($kri->bobot == 3) {
                                                                                                    echo 'checked';
                                                                                                  } ?>>
                <label class="form-check-label" for="inlineRadio3">3 </label>
              </div>
              <div class="form-check form-check-inline mr-3">
                <input class="form-check-input" type="radio" name="bobot" id="bobot" value="4" <?php if ($kri->bobot == 4) {
                                                                                                    echo 'checked';
                                                                                                  } ?>>
                <label class="form-check-label" for="inlineRadio3">4 </label>
              </div>
              <div class="form-check form-check-inline mr-3">
                <input class="form-check-input" type="radio" name="bobot" id="bobot" value="5" <?php if ($kri->bobot == 5) {
                                                                                                    echo 'checked';
                                                                                                  } ?>>
                <label class="form-check-label" for="inlineRadio3">5 </label>
              </div> -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary tombol-ubah"> Ubah </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!--
<script>
  $(function () {
    $('tb_kriteria').DataTable()
  })
</script>
-->