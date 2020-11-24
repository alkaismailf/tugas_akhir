<?php
    $query = $this->db->query("SELECT `tb_nilai_alternatif`.*,`tb_alternatif`. `nama_alternatif` ,`tb_kriteria`. *
            FROM `tb_nilai_alternatif` JOIN `tb_alternatif`ON `tb_nilai_alternatif`.`id_alternatif` = `tb_alternatif`.`id_alternatif`
            JOIN `tb_kriteria`ON `tb_nilai_alternatif`.`id_kriteria` = `tb_kriteria`.`id_kriteria`");

    $data           = array();
    $kriterias      = array();
    $bobot          = array();
    $nilai_kuadrat  = array();
    $atribut        = array();

    if ($query) {
        foreach ($query->result() as $row) {
            if (!isset($data[$row->nama_alternatif])) {
                $data[$row->nama_alternatif] = array();
            }
            if (!isset($data[$row->nama_alternatif][$row->nama_kriteria])) {
                $data[$row->nama_alternatif][$row->nama_kriteria] = array();
            }
            if (!isset($nilai_kuadrat[$row->nama_kriteria])) {
                $nilai_kuadrat[$row->nama_kriteria] = 0;
            }
            $bobot[$row->nama_kriteria]                         = $row->bobot;
            $data[$row->nama_alternatif][$row->nama_kriteria]   = $row->nilai;
            $nilai_kuadrat[$row->nama_kriteria]                += pow($row->nilai, 2);
            $kriterias[]                                        = $row->nama_kriteria;
            $tipe_kriteria[$row->nama_kriteria]                 = $row->tipe_kriteria;
        }
    }
    $kriteria     = array_unique($kriterias);
    $jml_kriteria = count($kriteria);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Halaman Perhitungan SPK</h1>

    <!-- DataTables Matriks Evaluasi -->
    <div class="card shadow mb-4">
        <a href="#collapse1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse1">
            <h6 class="m-0 font-weight-bold text-primary"> Matriks Evaluasi (x<sub>ij</sub>)</h6>
        </a>
        <div class="collapse show" id="collapse1">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="tabelkategori" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th rowspan='3'>No</th>
                                <th rowspan='3'>Alternatif</th>
                                <th rowspan='3'>Nama</th>
                                <th colspan='<?= $jml_kriteria; ?>'>Kriteria</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($kriteria as $k)
                                    echo "<th>$k</th>\n";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                for ($n = 1; $n <= $jml_kriteria; $n++)
                                    echo "<th>K$n</th>";
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($data as $nama => $krit) {
                                echo "<tr>
                            <td>" . (++$i) . "</td>
                            <th>A$i</th>
                            <td>$nama</td>";
                                foreach ($kriteria as $k) {
                                    echo "<td align='center'>$krit[$k]</td>";
                                }
                                echo "</tr>\n";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Normalisasi Matriks -->
    <div class="card shadow mb-4">
        <a href="#collapse2" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse2">
            <h6 class="m-0 font-weight-bold text-primary"> Matriks Ternormalisasi (x<sub>ij</sub>)</h6>
        </a>
        <div class="collapse hide" id="collapse2">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="tabelkategori" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th rowspan='3'>No</th>
                                <th rowspan='3'>Alternatif</th>
                                <th rowspan='3'>Nama</th>
                                <th colspan='<?= $jml_kriteria; ?>'>Kriteria</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($kriteria as $k)
                                    echo "<th>$k</th>\n";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                for ($n = 1; $n <= $jml_kriteria; $n++)
                                    echo "<th>K$n</th>";
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($data as $nama => $krit) {
                                echo "<tr>
                            <td>" . (++$i) . "</td>
                            <th>A{$i}</th>
                            <td>{$nama}</td>";
                                foreach ($kriteria as $k) {
                                    echo "<td align='center'>" . round(($krit[$k] / sqrt($nilai_kuadrat[$k])), 4) . "</td>";
                                }
                                echo
                                    "</tr>\n";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Rating Normalisasi Terbobot -->
    <div class="card shadow mb-4">
        <a href="#collapse3" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse3">
            <h6 class="m-0 font-weight-bold text-primary"> Matriks Normalisasi Terbobot (x<sub>ij</sub>)</h6>
        </a>
        <div class="collapse hide" id="collapse3">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="tabelkategori" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th rowspan='3'>No</th>
                                <th rowspan='3'>Alternatif</th>
                                <th rowspan='3'>Nama</th>
                                <th colspan='<?= $jml_kriteria; ?>'>Kriteria</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($kriteria as $k)
                                    echo "<th>$k</th>\n";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                for ($n = 1; $n <= $jml_kriteria; $n++)
                                    echo "<th>K$n</th>";
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $y = array();
                            foreach ($data as $nama => $krit) {
                                echo "<tr>
                            <td>" . (++$i) . "</td>
                            <th>A{$i}</th>
                            <td>$nama</td>";
                                foreach ($kriteria as $k) {
                                    $y[$k][$i - 1] = round(($krit[$k] / sqrt($nilai_kuadrat[$k])), 4) * $bobot[$k];
                                    echo "<td align='center'>" . $y[$k][$i - 1] . "</td>";
                                }
                                echo
                                    "</tr>\n";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- DataTales ideal positif -->
    <div class="card shadow mb-4">
        <a href="#collapse4" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse4">
            <h6 class="m-0 font-weight-bold text-primary"> Solusi Ideal Positif (A<sup>+</sup>)</h6>
        </a>
        <div class="collapse hide" id="collapse4">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="tabelkategori" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th colspan='<?= $jml_kriteria; ?>'>Kriteria</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($kriteria as $k)
                                    echo "<th>$k</th>\n";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                for ($n = 1; $n <= $jml_kriteria; $n++)
                                    echo "<th>y<sub>{$n}</sub><sup>+</sup></th>";
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                foreach ($kriteria as $k) {

                                    if ($tipe_kriteria[$k] == 0) {
                                        $yplus[$k] = ([$k] ? max($y[$k]) : min($y[$k]));
                                    } else if ($tipe_kriteria[$k] == 1) {
                                        $yplus[$k] = [$k] ? min($y[$k]) : max($y[$k]);
                                    }

                                    echo "<th>$yplus[$k]</th>";
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- DataTales Rating ideal negatif -->
    <div class="card shadow mb-4">
        <a href="#collapse5" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse5">
            <h6 class="m-0 font-weight-bold text-primary"> Solusi Ideal Negatif (A<sup>-</sup>)</h6>
        </a>
        <div class="collapse hide" id="collapse5">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="tabelkategori" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th colspan='<?= $jml_kriteria; ?>'>Kriteria</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($kriteria as $k)
                                    echo "<th>$k</th>\n";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                for ($n = 1; $n <= $jml_kriteria; $n++)
                                    echo "<th>y<sub>{$n}</sub><sup>+</sup></th>";
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $ymin = array();
                                foreach ($kriteria as $k) {

                                    if ($tipe_kriteria[$k] == 1) {
                                        $ymin[$k] = ([$k] ? max($y[$k]) : min($y[$k]));
                                    } else if ($tipe_kriteria[$k] == 0) {
                                        $ymin[$k] = [$k] ? min($y[$k]) : max($y[$k]);
                                    }
                                    echo "<th>$ymin[$k]</th>";
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales jarak positif-->
    <div class="card shadow mb-4">
        <a href="#collapse6" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse6">
            <h6 class="m-0 font-weight-bold text-primary"> Jarak Positif (D<sub>i</sub><sup>+</sup>)</h6>
        </a>
        <div class="collapse hide" id="collapse6">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelkategori" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th>No</th>
                                <th>Alternatif</th>
                                <th>Nama</th>
                                <th>D<suo>+</sup></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $dplus = array();
                            foreach ($data as $nama => $krit) {
                                echo "<tr>
                            <td>" . (++$i) . "</td>
                            <th>A{$i}</th>
                            <td>$nama</td>";
                                foreach ($kriteria as $k) {
                                    if (!isset($dplus[$i - 1])) $dplus[$i - 1] = 0;
                                    $dplus[$i - 1] += pow($yplus[$k] - $y[$k][$i - 1], 2);
                                }
                                echo "<td>" . round(sqrt($dplus[$i - 1]), 6) . "</td>
                                </tr>\n";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales jarak negatif-->
    <div class="card shadow mb-4">
        <a href="#collapse7" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse7">
            <h6 class="m-0 font-weight-bold text-primary">Jarak Negatif (D<sub>i</sub><sup>-</sup>)</h6>
        </a>
        <div class="collapse hide" id="collapse7">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="tabelkategori" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th>No</th>
                                <th>Alternatif</th>
                                <th>Nama</th>
                                <th>D<suo>-</sup></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $dmin = array();
                            foreach ($data as $nama => $krit) {
                                echo "<tr>
                          <td>" . (++$i) . "</td>
                          <th>A{$i}</th>
                          <td>{$nama}</td>";
                                foreach ($kriteria as $k) {
                                    if (!isset($dmin[$i - 1])) $dmin[$i - 1] = 0;
                                    $dmin[$i - 1] += pow($ymin[$k] - $y[$k][$i - 1], 2);
                                }
                                echo "<td>" . round(sqrt($dmin[$i - 1]), 4) . "</td>
                         </tr>\n";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Nilai Preferensi-->
    <div class="card shadow mb-4">
        <a href="#collapse8" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse8">
            <h6 class="m-0 font-weight-bold text-primary"> Nilai Preferensi(V<sub>i</sub>)</h6>
        </a>
        <div class="collapse hide" id="collapse8">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="tabelpreferensi" class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th>No</th>
                                <th>Alternatif</th>
                                <th>Nama</th>
                                <th><sub>V<sub>i</sub></sub></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $V = array();
                            foreach ($data as $nama => $krit) {
                                echo "<tr>
                          <td>" . (++$i) . "</td>
                          <th>A{$i}</th>
                          <td>{$nama}</td>";
                                foreach ($kriteria as $k) {
                                    $V = round(sqrt($dmin[$i - 1]) / (sqrt($dmin[$i - 1]) + sqrt($dplus[$i - 1])), 7);
                                }
                                echo "<td>{$V}</td></tr>\n";
                                $this->db->set('nilai_preferensi', $V);
                                $this->db->where('nama_alternatif', $nama);
                                $this->db->update('tb_alternatif');
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <a href="<?= base_url('Alternatif/indexhasil') ?>" class="btn btn-success mb-3"> Hasil SPK <i class="fa fa-arrow-right"></i></a>

</div>
<!-- /.container-fluid -->