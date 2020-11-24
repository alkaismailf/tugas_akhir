<?php 
/**
* 
*/
class Alternatif extends CI_controller
{

	public function __construct()
 	{
 		parent::__construct();

 		if ($this->session->userdata('role_id') != '1') {
 			$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				  Anda Belum Login!
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>');
 			redirect('auth/login');
 		}
 	}
	
	public function index()
 	{
        $data['alter'] = $this->m_alternatif->getalter();
 		$data['penilaian'] = $this->m_alternatif->getpenilaian();
 		$data['kriteria'] = $this->db->get('tb_kriteria')->result_array();
 		//$data['alternatif'] = $this->db->get('tb_alternatif')->result_array();
 		$this->load->view('templates/header');
 		$this->load->view('templates/sidebar');
 		$this->load->view('admin/alternatif', $data);
 		$this->load->view('templates/footer');
 	}

    public function indexperhitunganspk()
    {
        $data['penilaian'] = $this->m_alternatif->getpenilaian();
        $nilai = $this->m_alternatif->getnilai();
        $data['nilai'] = json_encode($nilai);

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/perhitungan_spk', $data);
        $this->load->view('templates/footer');
    }

 	public function indexhasil()
 	{
 		$data['alternatif'] = $this->m_alternatif->tampil_data();
 		$this->load->view('templates/header');
 		$this->load->view('templates/sidebar');
 		$this->load->view('admin/hasil_spk', $data);
 		$this->load->view('templates/footer');
 	}

 	public function tambahAlternatif()
 	{
 		$nama_alternatif = $this->input->post('nama_alternatif');

 		$data = array(
 			'nama_alternatif' => $nama_alternatif,
 		);

 		$this->m_alternatif->tambah($data, 'tb_alternatif');
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data berhasil ditambahkan!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');     
 		redirect('alternatif/index');
 	}

 	public function hapusAlternatif($id)
 	{
 		$where = array('id_alternatif' => $id);
 		
        $this->m_alternatif->hapuspenilaian($where, 'tb_nilai_alternatif');
 		$this->m_alternatif->hapus($where, 'tb_alternatif');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  Data telah terhapus!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
        $this->topsis();
 		redirect('alternatif/index');
 	}

    public function exportpdf()
    {       
        /*
        $this->load->library('dompdf_gen');
        $data['alternatif'] = $this->m_alternatif->tampil_data();

        $this->load->view('admin/export_pdf', $data);

        $paper_size = 'A4';
        $orientation = 'landscape';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);

        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream('Ranking_SPK.pdf', array('Attachment' => 0));
        */

        $this->load->library('pdfgenerator');
        $data['alternatif'] = $this->m_alternatif->tampil_data();
 
        $this->pdfgenerator->setPaper('A4', 'potrait');
        $this->pdfgenerator->filename = "Laporan Hasil SPK.pdf";
        $this->pdfgenerator->load_view('admin/hasil_spk_pdf', $data);
    }

    public function penilaian()
    {
        $kriteria   = $_POST['kriteria'];
        $nilai      = $_POST['nilai'];
        $id         = $_POST['id_alternatif'];
        $data       = array();
        $i          = 0;
        foreach ($kriteria as $k) {
            if ($nilai[$i] == 0) {
                $this->session->set_flashdata('flash', 'error2');
                redirect('alternatif/index');
            } else  if ($nilai[$i] != 0) {
                array_push($data, array(
                    'id_alternatif' => $id,
                    'id_kriteria' => $k,
                    'nilai' => $nilai[$i],
                ));
                $i++;
            }
        }
        $this->db->insert_batch('tb_nilai_alternatif', $data);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data berhasil diinput!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
        $this->topsis();
    }

 	public function ubahPenilaian()
    {
        $kriteria 	= $_POST['kriteria'];
        $nilai 		= $_POST['nilai'];
        $id 		= $_POST['id_alternatif'];
        $data 		= array();
        $i 			= 0;
        foreach ($kriteria as $k) {
            $hasil = $this->db->query("UPDATE tb_nilai_alternatif SET nilai = '$nilai[$i]' WHERE id_kriteria = '$k' AND id_alternatif = '$id'");

            // $query = "UPDATE nilai FROM "
            // array_push($data, array(
            //     'id_pemasok' => $id,
            //     'id_kriteria' => $k,
            //     'nilai' => $nilai[$i],
            // ));
            $i++;
        }
        // $this->db->insert_batch('penilaian_pemasok', $data);
        $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible fade show" role="alert">
                  Data berhasil diubah!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
        $this->topsis();
    }

    public function topsis()
    {
        $query = $this->db->query("SELECT `tb_nilai_alternatif`.*,`tb_alternatif`. `nama_alternatif` ,`tb_kriteria`. *
        FROM `tb_nilai_alternatif` JOIN `tb_alternatif`ON `tb_nilai_alternatif`.`id_alternatif` = `tb_alternatif`.`id_alternatif`
        JOIN `tb_kriteria`ON `tb_nilai_alternatif`.`id_kriteria` = `tb_kriteria`.`id_kriteria`");

        $data      		= array();
        $kriterias 		= array();
        $bobot     		= array();
        $nilai_kuadrat 	= array();
        $atribut 		= array();

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
        $kriteria     	= array_unique($kriterias);
        $jml_kriteria 	= count($kriteria);
        $i 				= 0;
        $y 				= array();
        foreach ($data as $nama => $krit) {
            ++$i;
            foreach ($kriteria as $k) {
                $y[$k][$i - 1] = round(($krit[$k] / sqrt($nilai_kuadrat[$k])), 4) * $bobot[$k];
            }
        }
        foreach ($kriteria as $k) {

            if ($tipe_kriteria[$k] == 0) {
                $yplus[$k] = ([$k] ? max($y[$k]) : min($y[$k]));
            } else if ($tipe_kriteria[$k] == 1) {
                $yplus[$k] = [$k] ? min($y[$k]) : max($y[$k]);
            }
        }
        $ymin = array();
        foreach ($kriteria as $k) {

            if ($tipe_kriteria[$k] == 1) {
                $ymin[$k] = ([$k] ? max($y[$k]) : min($y[$k]));
            } else if ($tipe_kriteria[$k] == 0) {
                $ymin[$k] = [$k] ? min($y[$k]) : max($y[$k]);
            }
        }
        $i = 0;
        $dplus = array();
        foreach ($data as $nama => $krit) {
            ++$i;
            foreach ($kriteria as $k) {
                if (!isset($dplus[$i - 1])) $dplus[$i - 1] = 0;
                $dplus[$i - 1] += pow($yplus[$k] - $y[$k][$i - 1], 2);
            }
        }
        $i = 0;
        $dmin = array();
        foreach ($data as $nama => $krit) {
            ++$i;
            foreach ($kriteria as $k) {
                if (!isset($dmin[$i - 1])) $dmin[$i - 1] = 0;
                $dmin[$i - 1] += pow($ymin[$k] - $y[$k][$i - 1], 2);
            }
        }
        $i = 0;
        $V = array();
        foreach ($data as $nama => $krit) {
            ++$i;
            foreach ($kriteria as $k) {
                $V = round(sqrt($dmin[$i - 1]) / (sqrt($dmin[$i - 1]) + sqrt($dplus[$i - 1])), 7);
            }
            $this->db->set('nilai_preferensi', $V);
            $this->db->where('nama_alternatif', $nama);
            $this->db->update('tb_alternatif');
        }
        redirect('alternatif/index');
    }

}
 ?>