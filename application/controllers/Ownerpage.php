<?php 
/**
 * 
 */
 class Ownerpage extends CI_Controller
 {
 	
 	
	public function __construct()
 	{
 		parent::__construct();

 		if ($this->session->userdata('role_id') != '2') {
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
 		$data['alternatif'] = $this->m_alternatif->tampil_data();
 		$this->load->view('temp_owner/header');
 		$this->load->view('temp_owner/sidebar');
 		$this->load->view('owner/dashboard', $data);
 		$this->load->view('temp_owner/footer');
 	}
	
	public function indexhasil_apriori()
    {


        $data['confidence'] = $this->m_apriori->tampil_hasil();
        $data['process_log'] = $this->db->get('tb_process_log')->result();
        
        $this->load->view('temp_owner/header');
        $this->load->view('temp_owner/sidebar');
        $this->load->view('owner/hasil_apriori', $data);
        $this->load->view('temp_owner/footer');
    }

 	public function indexhasil_spk()
 	{
 		$data['alternatif'] = $this->m_alternatif->tampil_data();
 		$this->load->view('temp_owner/header');
 		$this->load->view('temp_owner/sidebar');
 		$this->load->view('owner/hasil_spk', $data);
 		$this->load->view('temp_owner/footer');
 	}

 } 

 ?>