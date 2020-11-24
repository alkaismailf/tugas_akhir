<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Menu Utama</h1>
    </div>
    <!-- Content Row -->
      <div class="row">
          <!-- Area Chart -->
          <div class="col-xl-6 col-lg-6">
            <?php //foreach ($alternatif as $alt) : ?>
              <div class="card shadow mb-4  ">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Peringkat Hasil SPK</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <!-- Grafik Rangking -->
                  <div id="grafik">
                  </div>
                </div>
              </div>
            <?php //endforeach; ?>
          </div>  

          <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4  ">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Tabel Ranking</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <!-- Tabel Alternatif -->
                  <div class="table-responsive">
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
          </div>      

      </div>

          <!-- Content Row -->
</div>