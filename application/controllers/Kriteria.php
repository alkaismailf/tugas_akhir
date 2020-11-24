<?php 
/**
* 
*/
class Kriteria extends CI_controller
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
 		$data['kriteria'] = $this->m_kriteria->tampil_data()->result();
 		//$data['kode'] = $this->m_kriteria->kode();
 		$this->load->view('templates/header');
 		$this->load->view('templates/sidebar');
 		$this->load->view('admin/kriteria', $data);
 		$this->load->view('templates/footer');
 	}

 	public function tambahKriteria()
 	{
 		/*$nama_kriteria = $this->input->post('nama_kriteria');
 		$tipe_kriteria = $this->input->post('tipe_kriteria');
 		$bobot = $this->input->post('bobot');

 		$data = array(
 			'nama_kriteria' => $nama_kriteria,
 			'tipe_kriteria' => $tipe_kriteria,
 			'bobot' 		=> $bobot,
 		);

 		$this->m_kriteria->tambah($data, 'tb_kriteria');
 		redirect('kriteria/index');*/

 		$data['kriteria'] = $this->db->get('tb_kriteria')->result_array();
        $data['kode'] = $this->m_kriteria->kode();
        $this->form_validation->set_rules('nama_kriteria', 'nama_kriteria', 'required', ['required' => 'Nama kriteria tidak boleh kosong!']);
        $this->form_validation->set_rules('tipe_kriteria', 'tipe_kriteria', 'required', ['required' => 'Pilih tipe kriteria!']);
        $this->form_validation->set_rules('bobot', 'bobot', 'required', ['required' => 'Pilih bobot kriteria!']);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar');
            $this->load->view('admin/kriteria', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'id_kriteria'   => $this->input->post('id_kriteria', true),
                'nama_kriteria' => $this->input->post('nama_kriteria', true),
                'tipe_kriteria' => $this->input->post('tipe_kriteria', true),
                'bobot'         => $this->input->post('bobot', true)
            ];
            $this->db->insert('tb_kriteria', $data);

            //$krit  = $this->input->post('id_kriteria', true);
            $krit  = $this->db->insert_id();
            $alter = $this->m_kriteria->getalt();
            foreach ($alter as $alt) {
                $alternatif[] = $alt['id_alternatif'];
            }
            $pgn = array_unique($alternatif);
            foreach ($pgn as $un) {
                $data2 = [
                    'id_alternatif' => $un,
                    'id_kriteria'   => $krit,
                    'nilai'         => 1
                ];
                $this->db->insert('tb_nilai_alternatif', $data2);
            }
            //$this->session->set_flashdata('flash', 'dimasukan');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data berhasil ditambahkan!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect('kriteria/index');
        }
 	}

 	public function ubahKriteria()
 	{
 		$id				= $this->input->post('id_kriteria');
 		$nama_kriteria 	= $this->input->post('nama_kriteria');
 		$tipe_kriteria 	= $this->input->post('tipe_kriteria');
 		$bobot			= $this->input->post('bobot');

 		$data = array(
 			'nama_kriteria' => $nama_kriteria,
 			'tipe_kriteria' => $tipe_kriteria,
 			'bobot' 		=> $bobot,
 		);

 		$where = array('id_kriteria' => $id);

 		$this->m_kriteria->ubah($where, $data, 'tb_kriteria');
        $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible fade show" role="alert">
                  Data berhasil diubah!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
 		$this->topsis();
 	}

 	public function hapusKriteria($id)
 	{
 		$where = array('id_kriteria' => $id);

        $this->m_kriteria->hapuspenilaian($where, 'tb_nilai_alternatif');
 		$this->m_kriteria->hapus($where, 'tb_kriteria');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  Data telah terhapus!
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
        redirect('kriteria/index');
    }

}
 ?>