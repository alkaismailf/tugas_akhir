<?php 
/**
 * 
 */
 class M_kriteria extends CI_Model
 {
 	
 	public function tampil_data()
 	{
 		return $this->db->get('tb_kriteria');
 	} 

 	public function tambah($data, $table)
 	{
 		$this->db->insert($table, $data);
 	}

 	/*public function ubah($where,$table)
 	{
 		return $this->db->get_where($table, $where);
 	}*/

 	public function ubah($where,$data,$table)
    {
	  	$this->db->where($where);
	  	$this->db->update($table, $data);
	}

	public function hapus($where,$table)
 	{
 		$this->db->where($where);
	  	$this->db->delete($table);
 	}

    public function hapuspenilaian($where)
    {
        //$this->db->from('tb_nilai_alternatif');
        $this->db->where($where);
        $this->db->delete('tb_nilai_alternatif');
        
        //$query = $this->db->get(); 
        //return $query->result();
    }

 	public function kode()
    {
        $this->db->select('RIGHT(tb_kriteria.id_kriteria,2) as kode', FALSE);
        $this->db->order_by('id_kriteria', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('tb_kriteria');  //cek dulu apakah sudah ada kode di tabel.    
        if ($query->num_rows() <> 0) {
            //cek kode jika telah tersedia    
            $data = $query->row();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;  //cek jika kode belum terdapat pada table
        }
        $batas = str_pad($kode, 2, "0", STR_PAD_LEFT);
        $kodetampil = $batas;  //format kode
        
        return $kodetampil;
    }

    public function getalt()
    {
        $query = "SELECT `tb_alternatif`.`id_alternatif` FROM `tb_alternatif` JOIN `tb_nilai_alternatif`ON `tb_alternatif`.`id_alternatif` = `tb_nilai_alternatif`.`id_alternatif`";

        return $this->db->query($query)->result_array();
    }
 }

 ?>