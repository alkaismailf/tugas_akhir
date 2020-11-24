<?php
$query = $this->db->query("SELECT `tb_nilai_alternatif`.*,`tb_alternatif`. `nama_alternatif` ,`tb_kriteria`. *
FROM `tb_nilai_alternatif` JOIN `tb_alternatif`ON `tb_nilai_alternatif`.`id_alternatif` = `tb_alternatif`.`id_alternatif`
JOIN `tb_kriteria`ON `tb_nilai_alternatif`.`id_kriteria` = `tb_kriteria`.`id_kriteria`");

$data       = array();
$id         = array();
$kriterias  = array();
$id_a       = array();
$id_k       = array();

if ($query) {
    foreach ($query->result() as $row) {
        if (!isset($id_a[$row->nama_alternatif])) {
            $id_a[$row->nama_alternatif] = array();
        }
        if (!isset($id_k[$row->nama_kriteria])) {
            $id_k[$row->nama_kriteria] = array();
        }
        if (!isset($id[$row->id_alternatif])) {
            $id[$row->id_alternatif] = array();
        }
        if (!isset($data[$row->nama_alternatif])) {
            $data[$row->nama_alternatif] = array();
        }
        if (!isset($data[$row->nama_alternatif][$row->nama_kriteria])) {
            $data[$row->nama_alternatif][$row->nama_kriteria] = array();
        }
        if (!isset($id[$row->id_alternatif][$row->nama_kriteria])) {
            $id[$row->id_alternatif][$row->nama_kriteria] = array();
        }
        $data[$row->nama_alternatif][$row->nama_kriteria] = $row->nilai;
        $id[$row->id_alternatif][$row->nama_kriteria]     = $row->nilai;
        $kriterias[]                                      = $row->nama_kriteria;
        $id_a[$row->nama_alternatif]                      = $row->id_alternatif;
        $id_k[$row->nama_kriteria]                        = $row->id_kriteria;
        $tipe_kriteria[$row->nama_kriteria]               = $row->tipe_kriteria;
    }
}
$kriteria     = array_unique($kriterias);
$jml_kriteria = count($kriteria);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Halaman Alternatif</h1>

  <!-- Tabel Alternatif -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tabel Alternatif</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <!--<div class="flash-data" data-flashdata="<?= $this->session->flashdata('pesan'); ?>"></div>-->
        <?= $this->session->flashdata('pesan') ?>
        <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newaltModal"><i class="fas fa-plus-circle fa-sm"></i> Tambah Alternatif</a>
        <a href="" class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#newubModal"><i class="fas fa-pencil-alt fa-sm"></i> Ubah Nilai Alternatif Baru</a>
        <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
          <thead>
            <tr align="center">
              <th rowspan="2">No.</th>
              <th rowspan="2">Alternatif</th>
              <th colspan="<?= $jml_kriteria; ?>">Kriteria</th>
              <th rowspan="2" colspan="2">Aksi</th>
            </tr>
            <tr align="center">
              <?php
                foreach ($kriteria as $k) {
                    echo "<th>$k</th>\n";
                }
              ?>
            </tr>
          </thead>
          <tbody>
              <?php
              $i = 0;
              foreach ($data as $nama => $krit) { ?>
                <tr>
                  <td align="center"><?= ++$i ?></td>
                  <td><?= $nama ?></td>
                <?php foreach ($kriteria as $k) { ?>
                  <td align='center'><?= $krit[$k] ?></td>
                <?php } ?>
                  <td align="center">
                      <a href="" class="btn btn-info btn-icon-split btn-sm" data-toggle="modal" data-target="#editModal<?= $id_a[$nama]; ?>">
                        <span class="icon text-white-0">
                            <i class="far fa-edit"></i>
                        </span>
                      </a>
                  </td>      
                  <td align="center" onclick="javascript: return confirm('Hapus data?')">
                    <?= anchor('alternatif/hapusAlternatif/'.$id_a[$nama], '<div class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></div>') ?>
                  </td>
                </tr>
              <?php }  ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

<!-- Modal tambah data Alternatif -->
<div class="modal fade" id="newaltModal" tabindex="-1" role="dialog" aria-labelledby="newaltModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newkritModalLabel">Tambah Alternatif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('alternatif/tambahAlternatif'); ?>" method="post">
        <div class="modal-body">
          <!-- <input type="hidden" name="id_kriteria" value="<?= $kode ?>" readonly> -->
          <div class="form-group">
            <label>Nama Alternatif </label>
            <input type="text" class="form-control" id="nama_alternatif" name="nama alternatif" placeholder="Nama Alternatif" required>
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

<!-- Modal Tambah Nilai Alternatif -->
<div class="modal fade" id="newubModal" tabindex="-1" role="dialog" aria-labelledby="newubModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newkritModalLabel">Ubah Nilai Alternatif Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('alternatif/penilaian'); ?>" method="post">
        <div class="modal-body">
          <!-- <input type="hidden" name="id_kriteria" value="<?= $kode ?>" readonly> -->
          <label>Nama Alternatif </label>
          <?php foreach ($alter as $alt) { ?>
            <select id="id_alternatif" name="id alternatif" class="form-control">
                <option value="<?= $alt['id_alternatif'] ?>"> <?= $alt['nama_alternatif'] ?></option>
            </select>
          <?php } ?>
          <br>
          <?php foreach ($kriteria as $k) { ?>
            <label for=""><?= $k; ?></label>
            <input type="hidden" class="form-control" id="kriteria" name="kriteria[]" value="<?= $id_k[$k]; ?>" required>
            <!-- <input type="text" class="form-control" id="nilai" name="nilai[]" value="<?= $krit[$k]; ?>"> -->
                <?php if ($tipe_kriteria[$k] == 1) { ?>
                    <div class="input-group mb-3">
                        <select id="nilai" name="nilai[]" class="form-control">
                            <option value="1" <?php if ($krit[$k] == 1) {
                                                                echo 'selected';
                                                            } ?>> Sangat Rendah </option>
                            <option value="2" <?php if ($krit[$k] == 2) {
                                                                echo 'selected';
                                                          } ?>> Cukup Rendah </option>
                            <option value="3" <?php if ($krit[$k] == 3) {
                                                                echo 'selected';
                                                            } ?>> Sedang </option>
                            <option value="4" <?php if ($krit[$k] == 4) {
                                                                echo 'selected';
                                                            } ?>> Cukup Tinggi </option>
                            <option value="5" <?php if ($krit[$k] == 5) {
                                                                echo 'selected';
                                                            } ?>> Sangat Tinggi </option>
                        </select>
                    </div>
                <?php } else { ?>
                     <div class="input-group mb-3">
                        <select id="nilai" name="nilai[]" class="form-control">
                            <option value="1" <?php if ($krit[$k] == 1) {
                                                                echo 'selected';
                                                            } ?>> Sangat Buruk </option>
                            <option value="2" <?php if ($krit[$k] == 2) {
                                                                echo 'selected';
                                                            } ?>> Buruk </option>
                            <option value="3" <?php if ($krit[$k] == 3) {
                                                                echo 'selected';
                                                            } ?>> Cukup </option>
                            <option value="4" <?php if ($krit[$k] == 4) {
                                                                echo 'selected';
                                                            } ?>> Baik </option>
                            <option value="5" <?php if ($krit[$k] == 5) {
                                                                echo 'selected';
                                                            } ?>> Sangat Baik </option>
                        </select>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Update Nilai Alternatif -->
<?php
foreach ($id as $ids => $krit) :
    ?>
    <div class="modal fade" id="editModal<?= $ids; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Ubah Penilaian Alternatif</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('alternatif/ubahPenilaian'); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="id_alternatif" value="<?= $ids; ?>">
                        <?php foreach ($kriteria as $k) { ?>
                            <label for=""><?= $k; ?></label>
                            <input type="hidden" class="form-control" id="kriteria" name="kriteria[]" value="<?= $id_k[$k]; ?>">
                            <!-- <input type="text" class="form-control" id="nilai" name="nilai[]" value="<?= $krit[$k]; ?>"> -->
                            <?php if ($tipe_kriteria[$k] == 1) { ?>
                                <div class="input-group mb-3">
                                    <select id="nilai" name="nilai[]" class="form-control">
                                        <option value="1" <?php if ($krit[$k] == 1) {
                                                                            echo 'selected';
                                                                        } ?>> Sangat Rendah </option>
                                        <option value="2" <?php if ($krit[$k] == 2) {
                                                                            echo 'selected';
                                                                        } ?>> Cukup Rendah </option>
                                        <option value="3" <?php if ($krit[$k] == 3) {
                                                                            echo 'selected';
                                                                        } ?>> Sedang </option>
                                        <option value="4" <?php if ($krit[$k] == 4) {
                                                                            echo 'selected';
                                                                        } ?>> Cukup Tinggi </option>
                                        <option value="5" <?php if ($krit[$k] == 5) {
                                                                            echo 'selected';
                                                                        } ?>> Sangat Tinggi </option>
                                    </select>
                                </div>
                            <?php } else { ?>
                                <div class="input-group mb-3">
                                    <select id="nilai" name="nilai[]" class="form-control">
                                        <option value="1" <?php if ($krit[$k] == 1) {
                                                                            echo 'selected';
                                                                        } ?>> Sangat Buruk </option>
                                        <option value="2" <?php if ($krit[$k] == 2) {
                                                                            echo 'selected';
                                                                        } ?>> Buruk </option>
                                        <option value="3" <?php if ($krit[$k] == 3) {
                                                                            echo 'selected';
                                                                        } ?>> Cukup </option>
                                        <option value="4" <?php if ($krit[$k] == 4) {
                                                                            echo 'selected';
                                                                        } ?>> Baik </option>
                                        <option value="5" <?php if ($krit[$k] == 5) {
                                                                            echo 'selected';
                                                                        } ?>> Sangat Baik </option>
                                    </select>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary tombol-ubah"> Ubah </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>