<?php 
/**
 * 
 */
 class Dashboard extends CI_Controller
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
 		//$nilai = $this->m_alternatif->getnilai();
        //$data['nilai'] = json_encode($nilai);
 		$data['alternatif'] = $this->m_alternatif->tampil_data();
 		$data['confidence'] = $this->m_apriori->tampil_hasil();

 		$this->load->view('templates/header');
 		$this->load->view('templates/sidebar');
 		$this->load->view('admin/dashboard', $data);
 		$this->load->view('templates/footer');
 	}
 } 

 ?>