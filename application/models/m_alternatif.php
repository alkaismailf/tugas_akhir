<?php 
/**
 * 
 */
 class M_alternatif extends CI_Model
 {
 	
 	public function tampil_data()
 	{
        $this->db->from('tb_alternatif');
        $this->db->where('nilai_preferensi is NOT NULL', NULL, FALSE);
        $this->db->order_by("nilai_preferensi", "desc");
        
        $query = $this->db->get(); 
 		return $query->result();
 	}

    /*public function tampil_pdf($table)
    {        
        return $this->db->get($table);
    }*/

 	public function tambah($data, $table)
 	{
 		$this->db->insert($table, $data);
 	}

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

 	public function getpenilaian()
    {
        $query = "SELECT `tb_nilai_alternatif`.*,`tb_alternatif`.* ,`tb_kriteria`.*
        FROM `tb_nilai_alternatif` JOIN `tb_alternatif`ON `tb_nilai_alternatif`.`id_alternatif` = `tb_alternatif`.`id_alternatif`
        JOIN `tb_kriteria`ON `tb_nilai_alternatif`.`id_kriteria` = `tb_kriteria`.`id_kriteria`";
        
        return $this->db->query($query)->result_array();
    }

    public function hapuspenilaian($where)
    {
        //$this->db->from('tb_nilai_alternatif');
        $this->db->where($where);
        $this->db->delete('tb_nilai_alternatif');
        
        //$query = $this->db->get(); 
        //return $query->result();
    }

    public function getnilai()
    {
        $query = "SELECT * FROM `tb_alternatif` ORDER BY `nilai_preferensi`DESC ";

        return $this->db->query($query)->result_array();
    }

    public function getalter()
    {
        $query = "SELECT * FROM `tb_alternatif` WHERE `nilai_preferensi` IS NULL";

        return $this->db->query($query)->result_array();
    }

 }

 ?>