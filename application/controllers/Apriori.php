<?php
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('max_execution_time', 1800);

class Apriori extends CI_Controller
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
        $data['transaksi'] = $this->m_apriori->tampil_data()->result();
        
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/Perhitungan_apriori', $data);
        $this->load->view('templates/footer');
    }

    public function indexhasil()
    {


        $data['confidence'] = $this->m_apriori->tampil_hasil();
        $data['process_log'] = $this->db->get('tb_process_log')->result();
        
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/hasil_apriori', $data);
        $this->load->view('templates/footer');
    }

    public function hapusalltransaksi()
    {        
        $this->m_apriori->hapus('tb_transaksi');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  Data telah terhapus!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
        redirect('Apriori/index');
    }

    public function input_alter()
    {        
        $alt        = $this->m_apriori->get_kombinasi();
        
        $data = array(
            'nama_alternatif' => $alt,
        );

        $this->db->query("INSERT INTO tb_alternatif (nama_alternatif) VALUES ".$alt);

        //$this->m_alternatif->tambah($data, 'tb_alternatif');
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data berhasil ditambahkan!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');     
        redirect('alternatif/index');
    }

    public function reset()
    {        
        $this->m_apriori->hapus('tb_itemset1');
        $this->m_apriori->hapus('tb_itemset2');
        $this->m_apriori->hapus('tb_itemset3');
        $this->m_apriori->hapus('tb_confidence');
        $this->m_apriori->hapus('tb_process_log');
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Reset berhasil!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
        redirect('Apriori/index');
    }

    /*public function get_produk_to_in($produk)
    {
        $ex = explode(",", $produk);
        //$temp = "";
        for ($i=0; $i < count($ex); $i++) { 

            $jml_key = array_keys($ex, $ex[$i]);
            if(count($jml_key)>1){
                unset($ex[$i]);
            }

            //$temp = $ex[$i];
        }
        return implode(",", $ex);
    }*/

    public function importexcel()
    {
        if ( isset($_POST['import'])) {

            $file = $_FILES['excel']['tmp_name'];

            // Medapatkan ekstensi file csv yang akan diimport.
            $ekstensi  = explode('.', $_FILES['excel']['name']);

            // Tampilkan peringatan jika submit tanpa memilih menambahkan file.
            if (empty($file)) {
                echo 'File tidak boleh kosong!';
            } else {
                // Validasi apakah file yang diupload benar-benar file csv.
                if (strtolower(end($ekstensi)) === 'csv' && $_FILES["excel"]["size"] > 0) {

                    $i = 0;
                    $handle = fopen($file, "r");
                    while (($row = fgetcsv($handle, 2048))) {
                        $i++;
                        if ($i == 1) continue;
                        
                        // Data yang akan disimpan ke dalam database
                        $data = [
                            'id'            => $row[0],
                            'tgl_transaksi' => $row[1],
                            'produk'        => $row[2],
                        ];

                        // Simpan data ke database.
                        $this->m_apriori->tambah($data, 'tb_transaksi');
                    }

                    fclose($handle);
                    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Data berhasil ditambahkan!
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>');
                    redirect('Apriori/index');

                } else {
                    echo 'Format file tidak valid!';
                }
            }
        }
    }

    public function hitung_apriori()
    {
        if ( isset($_POST['min_support']) && isset($_POST['min_confidence'])) {
            $min_supp      = $_POST['min_support'];
            $min_conf      = $_POST['min_confidence'];

            $data = array(
                'min_support'       => $min_supp,
                'min_confidence'    => $min_conf,
            );

            $this->m_apriori->tambahFilter($data);
            $id_process = $this->db->insert_id();
            $min_support   = $this->m_apriori->get_minsupport($id_process);
            $min_confidence   = $this->m_apriori->get_minconfidence($id_process);

            //get  transaksi data to array variable
            $sql_trans = $this->db->get('tb_transaksi');
                   //WHERE tgl_transaksi BETWEEN '$start_date' AND '$end_date' ";
            //$result_trans = $sql_trans->result();
            $dataTransaksi = $item_list = array();
            //$jumlah_transaksi = $this->m_apriori->get_numrows('tb_transaksi');
            $jumlah_transaksi = $sql_trans->num_rows();
            $min_support_relative = ((double)$min_supp/(double)$jumlah_transaksi)*100;
            $x = 0;
            $myrow = $sql_trans->result_array();
            foreach ($myrow as $key => $row) {
                $dataTransaksi[$x]['tanggal'] = $row['tgl_transaksi'];
                $item_produk = $row['produk'].",";
                //mencegah ada jarak spasi
                $item_produk = str_replace(" ,", ",", $item_produk);
                $item_produk = str_replace("  ,", ",", $item_produk);
                $item_produk = str_replace("   ,", ",", $item_produk);
                $item_produk = str_replace("    ,", ",", $item_produk);
                $item_produk = str_replace(", ", ",", $item_produk);
                $item_produk = str_replace(",  ", ",", $item_produk);
                $item_produk = str_replace(",   ", ",", $item_produk);
                $item_produk = str_replace(",    ", ",", $item_produk);
                
                $dataTransaksi[$x]['produk'] = $item_produk;
                $produk = explode(",", $row['produk']);
                //all items
                foreach ($produk as $key => $value_produk) {
                    //if(!in_array($value_produk, $item_list)){
                    if(!in_array(strtoupper($value_produk), array_map('strtoupper', $item_list))){
                        if(!empty($value_produk)){
                            $item_list[] = $value_produk;
                        }
                    }
                }
                $x++;
            }        
            
            //build itemset 1
            $itemset1 = $jumlahItemset1 = $supportItemset1 = $valueIn = array();
            $x=1;
            foreach ($item_list as $item) {
                $jumlah = $this->jumlah_itemset1($dataTransaksi, $item);
                $support = ($jumlah/$jumlah_transaksi) * 100;
                $lolos = ($support>=$min_support_relative)?"1":"0";
                $valueIn[] = "('$item','$jumlah','$support','$lolos','$id_process')";
                if($lolos){
                    $itemset1[]         = $item;//item yg lolos itemset1
                    $jumlahItemset1[]   = $jumlah;
                    $supportItemset1[]  = $support;
                }
                $x++;
            }
        
            //insert into itemset1 one query with many value
            $value_insert = implode(",", $valueIn);
            //$sql_insert_itemset1 = "INSERT INTO tb_itemset1 (atribut, jumlah, support, lolos, id_process) "
            //        . " VALUES ".$value_insert;
            $this->db->query("INSERT INTO tb_itemset1 (atribut, jumlah, support, lolos, id_process) VALUES ".$value_insert);
            //$this->m_apriori->tambahItemset1($value_insert);      
            
            //build itemset2
            $NilaiAtribut1 = $NilaiAtribut2 = array();
            $itemset2_var1 = $itemset2_var2 = $jumlahItemset2 = $supportItemset2 = array();
            $valueIn_itemset2 = array();
            $no=1;
            //$a = 0;
            for ($a = 0; $a < count($itemset1); $a++) 
            { 
                //$b = 0;
                for ($b = 0; $b < count($itemset1); $b++ )
                {
                    $variance1 = $itemset1[$a];
                    $variance2 = $itemset1[$b];
                    if (!empty($variance1) && !empty($variance2)) {
                        if ($variance1 != $variance2) {
                            if(!$this->is_exist_variasi_itemset($NilaiAtribut1, $NilaiAtribut2, $variance1, $variance2)) {
                                //$jml_itemset2 = get_count_itemset2($this->db, $variance1, $variance2, $start_date, $end_date);
                                $jml_itemset2 = $this->jumlah_itemset2($dataTransaksi, $variance1, $variance2);
                                $NilaiAtribut1[] = $variance1;
                                $NilaiAtribut2[] = $variance2;

                                $support2 = ($jml_itemset2/$jumlah_transaksi) * 100;
                                $lolos = ($support2 >= $min_support_relative)? 1:0;
                                
                                $valueIn_itemset2[] = "('$variance1','$variance2','$jml_itemset2','$support2','$lolos','$id_process')";
                                if($lolos){
                                    $itemset2_var1[] = $variance1;
                                    $itemset2_var2[] = $variance2;
                                    $jumlahItemset2[] = $jml_itemset2;
                                    $supportItemset2[] = $support2;
                                }
                                $no++;
                            }
                        }
                    }
                }
            }
        
            //insert into itemset2 one query with many value
            $value_insert_itemset2 = implode(",", $valueIn_itemset2);
            //$sql_insert_itemset2 = "INSERT INTO tb_itemset2 (atribut1, atribut2, jumlah, support, lolos, id_process) "
            //        . " VALUES ".$value_insert_itemset2;
            $this->db->query("INSERT INTO tb_itemset2 (atribut1, atribut2, jumlah, support, lolos, id_process) "." VALUES ".$value_insert_itemset2);
            //$this->m_apriori->tambahItemset2($value_insert_itemset2);
                    
            //build itemset3
            //$a = 0;
            $tigaVariasiItem = $valueIn_itemset3 =  array();
            $itemset3_var1 = $itemset3_var2 = $itemset3_var3 = $jumlahItemset3 = $supportItemset3 = array();
            $no=1;
            for ($a=0; $a < count($itemset2_var1); $a++) 
            { 
                //$b = 0;
                for ($b = 0; $b < count($itemset2_var1); $b++ )
                {
                    if($a != $b){
                        $itemset1a = $itemset2_var1[$a];
                        $itemset1b = $itemset2_var1[$b];

                        $itemset2a = $itemset2_var2[$a];
                        $itemset2b = $itemset2_var2[$b];

                        if (!empty($itemset1a) && !empty($itemset1b)&& !empty($itemset2a) && !empty($itemset2b)) {
                            
                            $temp_array = $this->get_variasi_itemset3($tigaVariasiItem, 
                                    $itemset1a, $itemset1b, $itemset2a, $itemset2b);
                            
                            if(count($temp_array)>0){
                                //variasi-variasi itemset isi ke array
                                $tigaVariasiItem = array_merge($tigaVariasiItem, $temp_array);
                                
                                foreach ($temp_array as $idx => $val_nilai) {
                                    $itemset1 = $itemset2 = $itemset3 = "";
                                    
                                    $aaa=0;
                                    foreach ($val_nilai as $idx1 => $v_nilai) {
                                        if($aaa==0){
                                            $itemset1 = $v_nilai;
                                        }
                                        if($aaa==1){
                                            $itemset2 = $v_nilai;
                                        }
                                        if($aaa==2){
                                            $itemset3 = $v_nilai;
                                        }
                                        $aaa++;
                                    }
                                    
                                    //jumlah item set3 dan menghitung supportnya
                                    //$jml_itemset3 = get_count_itemset3($this->db, $itemset1, $itemset2, $itemset3, $start_date, $end_date);
                                    $jml_itemset3 = $this->jumlah_itemset3($dataTransaksi, $itemset1, $itemset2, $itemset3);
                                    $support3 = ($jml_itemset3/$jumlah_transaksi) * 100;
                                    $lolos = ($support3 >= $min_support_relative)? 1:0;
                                    
                                    $valueIn_itemset3[] = "('$itemset1','$itemset2','$itemset3','$jml_itemset3','$support3','$lolos','$id_process')";
                                    
                                    if($lolos){
                                        $itemset3_var1[] = $itemset1;
                                        $itemset3_var2[] = $itemset2;
                                        $itemset3_var3[] = $itemset3;
                                        $jumlahItemset3[] = $jml_itemset3;
                                        $supportItemset3[] = $support3;
                                    }
                                    $no++;
                                }
                            }
                        }
                    }
                }
            }

            //insert into itemset3 one query with many value
            $value_insert_itemset3 = implode(",", $valueIn_itemset3);
            //$sql_insert_itemset3 = "INSERT INTO tb_itemset3(atribut1, atribut2, atribut3, jumlah, support, lolos, id_process) "
            //        . " VALUES ".$value_insert_itemset3;
            $this->db->query("INSERT INTO tb_itemset3 (atribut1, atribut2, atribut3, jumlah, support, lolos, id_process) "." VALUES ".$value_insert_itemset3);
            //$this->m_apriori->tambahItemset3($value_insert_itemset3);
                    
            //hitung confidence
            $confidence_from_itemset = 0;
            //dari itemset 3 jika tidak ada yg lolos ambil dari itemset 2 jika tiak ada gagal mendapatkan confidence
            //$sql_3 = "SELECT * FROM tb_itemset3 WHERE lolos = 1 AND id_process = ".$id_process;
            $res_3 = $this->m_apriori->get_itemset('tb_itemset3', $id_process)->result();
            $jumlah_itemset3_lolos = $this->m_apriori->get_baris('tb_itemset3', $id_process);
            if($jumlah_itemset3_lolos > 0){
                $confidence_from_itemset = 3;
                $row_3 = $this->m_apriori->get_itemset('tb_itemset3', $id_process)->result_array();
                
                foreach ($row_3 as $key => $row3) {
                    $atribut1 = $row3['atribut1'];
                    $atribut2 = $row3['atribut2'];
                    $atribut3 = $row3['atribut3'];
                    $supp_xuy = $row3['support'];
                    
                    //1,2 => 3
                    $this->hitung_confidence($supp_xuy, $min_supp, $min_conf, 
                            $atribut1, $atribut2, $atribut3, $id_process, $dataTransaksi, $jumlah_transaksi);
                    
                    //2,3 => 1
                    $this->hitung_confidence($supp_xuy, $min_supp, $min_conf, 
                            $atribut2, $atribut3, $atribut1, $id_process, $dataTransaksi, $jumlah_transaksi);
                    
                    //3,1 => 2
                    $this->hitung_confidence($supp_xuy, $min_supp, $min_conf, 
                            $atribut3, $atribut1, $atribut2, $id_process, $dataTransaksi, $jumlah_transaksi);
                    
                    
                    //1 => 3,2
                    $this->hitung_confidence1($supp_xuy, $min_supp, $min_conf, 
                            $atribut1, $atribut3, $atribut2, $id_process, $dataTransaksi, $jumlah_transaksi);
                    
                    //2 => 1,3
                    $this->hitung_confidence1($supp_xuy, $min_supp, $min_conf,
                            $atribut2, $atribut1, $atribut3, $id_process, $dataTransaksi, $jumlah_transaksi);
                    
                    //3 => 2,1
                    $this->hitung_confidence1($supp_xuy, $min_supp, $min_conf,
                            $atribut3, $atribut2, $atribut1, $id_process, $dataTransaksi, $jumlah_transaksi);
                }

            }

            //dari itemset 2
            //$sql_2 = "SELECT * FROM tb_itemset2 WHERE lolos = 1 AND id_process = ".$id_process;
            $res_2 = $this->m_apriori->get_itemset('tb_itemset2', $id_process)->result();
            $jumlah_itemset2_lolos = $this->m_apriori->get_baris('tb_itemset2', $id_process);
            if($jumlah_itemset2_lolos > 0){
                $confidence_from_itemset = 2;
                $row_2 = $this->m_apriori->get_itemset('tb_itemset2', $id_process)->result_array();

                foreach ($row_2 as $key => $row2) {
                    $atribut1 = $row2['atribut1'];
                    $atribut2 = $row2['atribut2'];
                    $supp_xuy = $row2['support'];
                    
                    //1 => 2
                    $this->hitung_confidence2($supp_xuy, $min_supp, $min_conf, $atribut1, $atribut2, $id_process, $dataTransaksi, $jumlah_transaksi);
                    
                    //2 => 1
                    $this->hitung_confidence2($supp_xuy, $min_supp, $min_conf, $atribut2, $atribut1, $id_process, $dataTransaksi, $jumlah_transaksi);
                }
            }
            if($confidence_from_itemset==0){
                return false;
            }
            return true;
            
            redirect('Apriori/indexhasil');
            
        }
        
    }

    public function is_exist_variasi_itemset($array_item1, $array_item2, $item1, $item2) 
    {    
        $bool1 = array_keys(array_map('strtoupper', $array_item1), strtoupper($item1));
        $bool2 = array_keys(array_map('strtoupper', $array_item2), strtoupper($item2));
        $bool3 = array_keys(array_map('strtoupper', $array_item2), strtoupper($item1));
        $bool4 = array_keys(array_map('strtoupper', $array_item1), strtoupper($item2));
        
        foreach ($bool1 as $key => $value) {
            $aa = array_search($value, $bool2);
            if(is_numeric($aa)){
                return true;
            }
        }
        
        foreach ($bool3 as $key => $value) {
            $aa = array_search($value, $bool4);
            if(is_numeric($aa)){
                return true;
            }
        }
        return false;
    }

    public function get_variasi_itemset3($array_itemset3, $item1, $item2, $item3, $item4) 
    {
        $return = array();
        
        $return1 = array();
        if(!in_array(strtoupper($item1), array_map('strtoupper', $return1))){
            $return1[] = $item1;
        }
        if(!in_array(strtoupper($item2), array_map('strtoupper', $return1))){
            $return1[] = $item2;
        }
        if(!in_array(strtoupper($item3), array_map('strtoupper', $return1))){
            $return1[] = $item3;
        }
        
        $return2 = array();
        if(!in_array(strtoupper($item1), array_map('strtoupper', $return2))){
            $return2[] = $item1;
        }
        if(!in_array(strtoupper($item2), array_map('strtoupper', $return2))){
            $return2[] = $item2;
        }
        if(!in_array(strtoupper($item4), array_map('strtoupper', $return2))){
            $return2[] = $item4;
        }
        
        $return3 = array();
        if(!in_array(strtoupper($item1), array_map('strtoupper', $return3))){
            $return3[] = $item1;
        }
        if(!in_array(strtoupper($item3), array_map('strtoupper', $return3))){
            $return3[] = $item3;
        }
        if(!in_array(strtoupper($item4), array_map('strtoupper', $return3))){
            $return3[] = $item4;
        }
        
        $return4 = array();
        if(!in_array(strtoupper($item2), array_map('strtoupper', $return4))){
            $return4[] = $item2;
        }
        if(!in_array(strtoupper($item3), array_map('strtoupper', $return4))){
            $return4[] = $item3;
        }
        if(!in_array(strtoupper($item4), array_map('strtoupper', $return4))){
            $return4[] = $item4;
        }
        
        if(count($return1)==3){
            if(!$this->is_exist_variasi_on_itemset3($return, $return1)){
                if(!$this->is_exist_variasi_on_itemset3($array_itemset3, $return1)){
                    $return[] = $return1;
                }
            }
        }
        if(count($return2)==3){
            if(!$this->is_exist_variasi_on_itemset3($return, $return2)){
                if(!$this->is_exist_variasi_on_itemset3($array_itemset3, $return2)){
                    $return[] = $return2;
                }
            }
        }
        if(count($return3)==3){
            if(!$this->is_exist_variasi_on_itemset3($return, $return3)){
                if(!$this->is_exist_variasi_on_itemset3($array_itemset3, $return3)){
                    $return[] = $return3;
                }
            }
        }
        if(count($return4)==3){
            if(!$this->is_exist_variasi_on_itemset3($return, $return4)){
                if(!$this->is_exist_variasi_on_itemset3($array_itemset3, $return4)){
                    $return[] = $return4;
                }
            }
        }

        return $return;
    }

    public function is_exist_variasi_on_itemset3($array, $tiga_variasi)
    {
        $return = false;
        
        foreach ($array as $key => $value) {
            $jml=0;
            foreach ($value as $key => $val1) {
                foreach ($tiga_variasi as $key => $val2) {
                    if(strtoupper($val1) == strtoupper($val2)){
                        $jml++;
                    }
                }
            }
            if($jml==3){
                $return=true;
                break;
            }
        }
        
        return $return;
    }

        /**
     * kombinasi atibut1 U atribut2 => $atribut3
     * save to table tb_confidence
     * @param type $this->db
     * @param type $supp_xuy
     * @param type $atribut1
     * @param type $atribut2
     * @param type $atribut3
     */
    public function hitung_confidence($supp_xuy, $min_supp, $min_conf,
            $atribut1, $atribut2, $atribut3, $id_process, $dataTransaksi, $jumlah_transaksi)
    {

        //hitung nilai support $nilai_support_x seperti di itemset2
        $jml_itemset2 = $this->jumlah_itemset2($dataTransaksi, $atribut1, $atribut2);
        $nilai_support_x = ($jml_itemset2/$jumlah_transaksi) * 100;
        
            $kombinasi1 = $atribut1." , ".$atribut2;
            $kombinasi2 = $atribut3;
            $supp_x = $nilai_support_x;//$row1_['support'];
            $conf = ($supp_xuy/$supp_x)*100;
            //lolos seleksi min confidence itemset3
            $lolos = ($conf >= $min_conf)? 1:0;
            
            //hitung korelasi lift
            $jumlah_kemunculanAB = $this->jumlah_itemset3($dataTransaksi, $atribut1, $atribut2, $atribut3);
            $PAUB = $jumlah_kemunculanAB/$jumlah_transaksi;
            
            $jumlah_kemunculanA = $this->jumlah_itemset2($dataTransaksi, $atribut1, $atribut2);
            $jumlah_kemunculanB = $this->jumlah_itemset1($dataTransaksi, $atribut3);
            
            //$nilai_uji_lift = $PAUB / $jumlah_kemunculanA * $jumlah_kemunculanB;
            $nilai_uji_lift = $PAUB / (($jumlah_kemunculanA/$jumlah_transaksi) * ($jumlah_kemunculanB/$jumlah_transaksi));
            $korelasi_rule = ($nilai_uji_lift<1)?"korelasi negatif":"korelasi positif";
            if($nilai_uji_lift==1){
                $korelasi_rule = "tidak ada korelasi";
            }
            
            //masukkan ke table confidence
            $data = array(
                'kombinasi1' => $kombinasi1,
                'kombinasi2' => $kombinasi2,
                'support_xUy' => $supp_xuy,
                'support_x' => $supp_x,
                'confidence' => $conf,
                'lolos' => $lolos,
                'min_support' => $min_supp,
                'min_confidence' => $min_conf,
                'nilai_uji_lift' => $nilai_uji_lift,
                'korelasi_rule' => $korelasi_rule,
                'id_process' => $id_process,
                'jumlah_a' => $jumlah_kemunculanA,
                'jumlah_b' => $jumlah_kemunculanB,
                'jumlah_ab' => $jumlah_kemunculanAB,
                'px' => ($jumlah_kemunculanA/$jumlah_transaksi),
                'py' => ($jumlah_kemunculanB/$jumlah_transaksi),
                'pxuy' => $PAUB,
                'from_itemset'=>3
            );
            $this->m_apriori->tambah($data, 'tb_confidence');
    }

        /**
     * confidence atribut1 => atribut2 U atribut3
     * @param type $this->db
     * @param type $supp_xuy
     * @param type $min_support
     * @param type $min_confidence
     * @param type $atribut1
     * @param type $atribut2
     * @param type $atribut3
     */
    public function hitung_confidence1($supp_xuy, $min_supp, $min_conf,
            $atribut1, $atribut2, $atribut3, $id_process, $dataTransaksi, $jumlah_transaksi)
    {
        
        //hitung nilai support seperti itemset1
        $jml_itemset1 = $this->jumlah_itemset1($dataTransaksi, $atribut1);
        $nilai_support_x = ($jml_itemset1/$jumlah_transaksi) * 100;
        
                $kombinasi1 = $atribut1;
                $kombinasi2 = $atribut2." , ".$atribut3;
                $supp_x = $nilai_support_x;//$row4_['support'];
                $conf = ($supp_xuy/$supp_x)*100;
                //lolos seleksi min confidence itemset3
                $lolos = ($conf >= $min_conf)? 1:0;
                
                //hitung korelasi lift
                $jumlah_kemunculanAB = $this->jumlah_itemset3($dataTransaksi, $atribut1, $atribut2, $atribut3);
                $PAUB = $jumlah_kemunculanAB/$jumlah_transaksi;

                $jumlah_kemunculanA = $this->jumlah_itemset1($dataTransaksi, $atribut1);
                $jumlah_kemunculanB = $this->jumlah_itemset2($dataTransaksi, $atribut2, $atribut3);

                $nilai_uji_lift = $PAUB / (($jumlah_kemunculanA/$jumlah_transaksi) * ($jumlah_kemunculanB/$jumlah_transaksi));
                $korelasi_rule = ($nilai_uji_lift<1)?"korelasi negatif":"korelasi positif";
                if($nilai_uji_lift==1){
                    $korelasi_rule = "tidak ada korelasi";
                }

                //masukkan ke table confidence
                $data = array(
                    'kombinasi1' => $kombinasi1,
                    'kombinasi2' => $kombinasi2,
                    'support_xUy' => $supp_xuy,
                    'support_x' => $supp_x,
                    'confidence' => $conf,
                    'lolos' => $lolos,
                    'min_support' => $min_supp,
                    'min_confidence' => $min_conf,
                    'nilai_uji_lift' => $nilai_uji_lift,
                    'korelasi_rule' => $korelasi_rule,
                    'id_process' => $id_process,
                    'jumlah_a' => $jumlah_kemunculanA,
                    'jumlah_b' => $jumlah_kemunculanB,
                    'jumlah_ab' => $jumlah_kemunculanAB,
                    'px' => ($jumlah_kemunculanA/$jumlah_transaksi),
                    'py' => ($jumlah_kemunculanB/$jumlah_transaksi),
                    'pxuy' => $PAUB,
                    'from_itemset'=>3
                );
            $this->m_apriori->tambah($data, 'tb_confidence');
    }

    public function hitung_confidence2($supp_xuy, $min_supp, $min_conf,
            $atribut1, $atribut2, $id_process, $dataTransaksi, $jumlah_transaksi)
    {
        
            //hitung nilai support seperti itemset1
            $jml_itemset1 = $this->jumlah_itemset1($dataTransaksi, $atribut1);
            $nilai_support_x = ($jml_itemset1/$jumlah_transaksi) * 100;
        
                $kombinasi1 = $atribut1;
                $kombinasi2 = $atribut2;
                $supp_x = $nilai_support_x;//$row1_['support'];
                $conf = ($supp_xuy/$supp_x)*100;
                //lolos seleksi min confidence itemset3
                $lolos = ($conf >= $min_conf)? 1:0;
                
                //hitung korelasi lift
                $jumlah_kemunculanAB = $this->jumlah_itemset2($dataTransaksi, $atribut1, $atribut2);
                $PAUB = $jumlah_kemunculanAB/$jumlah_transaksi;

                $jumlah_kemunculanA = $this->jumlah_itemset1($dataTransaksi, $atribut1);
                $jumlah_kemunculanB = $this->jumlah_itemset1($dataTransaksi, $atribut2);

                $nilai_uji_lift = $PAUB / (($jumlah_kemunculanA/$jumlah_transaksi) * ($jumlah_kemunculanB/$jumlah_transaksi));
                $korelasi_rule = ($nilai_uji_lift<1)?"korelasi negatif":"korelasi positif";
                if($nilai_uji_lift==1){
                    $korelasi_rule = "tidak ada korelasi";
                }
                
                //masukkan ke table confidence
                $data = array(
                    'kombinasi1' => $kombinasi1,
                    'kombinasi2' => $kombinasi2,
                    'support_xUy' => $supp_xuy,
                    'support_x' => $supp_x,
                    'confidence' => $conf,
                    'lolos' => $lolos,
                    'min_support' => $min_supp,
                    'min_confidence' => $min_conf,
                    'nilai_uji_lift' => $nilai_uji_lift,
                    'korelasi_rule' => $korelasi_rule,
                    'id_process' => $id_process,
                    'jumlah_a' => $jumlah_kemunculanA,
                    'jumlah_b' => $jumlah_kemunculanB,
                    'jumlah_ab' => $jumlah_kemunculanAB,
                    'px' => ($jumlah_kemunculanA/$jumlah_transaksi),
                    'py' => ($jumlah_kemunculanB/$jumlah_transaksi),
                    'pxuy' => $PAUB,
                    'from_itemset'=>2
                );
            $this->m_apriori->tambah($data, 'tb_confidence');
    }

    public function jumlah_itemset1($transaksi_list, $produk)
    {
        $count = 0;
        foreach ($transaksi_list as $key => $data) {
            $items = ",".strtoupper($data['produk']);
            $item_cocok = ",".strtoupper($produk).",";
            $pos = strpos($items, $item_cocok);
            if($pos!==false){//was found at position $pos
                $count++;
            }
        }
        return $count;
    }

    public function jumlah_itemset2($transaksi_list, $variasi1, $variasi2)
    {
        $count = 0;
        foreach ($transaksi_list as $key => $data) {
            $items = ",".strtoupper($data['produk']);
            $item_variasi1 = ",".strtoupper($variasi1).",";
            $item_variasi2 = ",".strtoupper($variasi2).",";
            
            $pos1 = strpos($items, $item_variasi1);
            $pos2 = strpos($items, $item_variasi2);
            if($pos1!==false && $pos2!==false){//was found at position $pos
                $count++;
            }
        }
        return $count;
    }

    public function jumlah_itemset3($transaksi_list, $variasi1, $variasi2, $variasi3)
    {
        $count = 0;
        foreach ($transaksi_list as $key => $data) {
            $items = ",".strtoupper($data['produk']);
            $item_variasi1 = ",".strtoupper($variasi1).",";
            $item_variasi2 = ",".strtoupper($variasi2).",";
            $item_variasi3 = ",".strtoupper($variasi3).",";
            
            $pos1 = strpos($items, $item_variasi1);
            $pos2 = strpos($items, $item_variasi2);
            $pos3 = strpos($items, $item_variasi3);
            if($pos1!==false && $pos2!==false && $pos3!==false){//was found at position $pos
                $count++;
            }
        }
        return $count;
    }

}
