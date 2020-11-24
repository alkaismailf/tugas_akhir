<div class="container-fluid">


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Menu Utama</h1>
    </div>
  <!-- Content Row -->
      <div class="row">
          
          <!--<div class="col-xl-6 col-lg-6">          
              <div class="card shadow mb-4  ">                
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Peringkat Hasil SPK</h6>
                </div>                
                <div class="card-body">
                  <div class="chart-bar">
                      <canvas id="grafik"></canvas>
                  </div>
                </div>
              </div> 
          </div>-->

          <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4  ">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Hasil Aturan Asosiasi</h6>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr align="center">
                          <th>No.</th>
                          <th>Itemset</th>
                          <th>Nilai Confidence</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($confidence as $conf) : ?>
                          <tr>
                            <td align="center" scope="row"><?= $i; ?></td>
                            <td><?= $conf->kombinasi1 ?> ---> <?= $conf->kombinasi2 ?></td>
                            <td align="center"><?= number_format($conf->confidence,2, ',', '.') ?></td>
                          </tr>
                          <?php $i++; ?>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
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

<script>
     /*$(document).ready(function() {
         Morris.Bar({
             element: 'myBarChart',
             data: <?= $nilai; ?>,
             xkey: 'nama_alternatif',
             ykeys: ['nilai_preferensi'],
             labels: ['Nilai Preferensi']
         });
     });*/

var ctx = document.getElementById('grafik').getContext('2d');
var grafik = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
        <?php foreach ($alternatif as $alt) {
          echo '$alt->nama_alternatif';
        } ?>
        ],
        datasets: [{
            label: '',
            data: [
            <?php foreach ($alternatif as $alt) {
              echo '$alt->nilai_preferensi';
            } ?>
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>