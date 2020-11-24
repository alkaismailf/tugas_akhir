<?php 
/**
 * 
 */
 class M_apriori extends CI_Model
 {
 	
 	public function tampil_data()
 	{
 		return $this->db->get('tb_transaksi');
 	}

    public function tampil_hasil()
    {
        $this->db->from('tb_confidence');
        $this->db->where('lolos', 1);
        $this->db->order_by("confidence", "desc");

        $query = $this->db->get();

        return $query->result();
    }

 	public function tambah($data, $table)
 	{
 		$this->db->insert($table, $data);
 	}

    public function tambahFilter($data)
    {
        $this->db->insert('tb_process_log', $data);
    }

    public function tambahItemset1($data)
    {
        $this->db->insert('tb_itemset1', $data);
    }

    public function tambahItemset2($data)
    {
        $this->db->insert('tb_itemset2', $data);
    }

    public function tambahItemset3($data)
    {
        $this->db->insert('tb_itemset3', $data);
    }

    public function hapus($table)
    {
        $this->db->truncate($table);
    }

    public function get_minsupport($id)
    {
        $this->db->select('min_support');
        $this->db->from('tb_process_log');
        $this->db->where('id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_minconfidence($id)
    {
        $this->db->select('min_confidence');
        $this->db->from('tb_process_log');
        $this->db->where('id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    /*public function get_numrows($table)
    {
        $query = $this->db->get($table);
        $num = $query->num_rows();

        return $num;
    }*/

    public function get_baris($table, $id)
    {
        $this->db->from($table);
        $this->db->where('lolos', 1);
        $this->db->where('id_process', $id);

        $query = $this->db->get();
        $num = $query->num_rows();

        return $num;
    }

    public function get_itemset($table, $id)
    {
        $this->db->from($table);
        $this->db->where('lolos', 1);
        $this->db->where('id_process', $id);

        $query = $this->db->get();

        return $query;
    }

    public function get_kombinasi()
    {
        $query = "SELECT kombinasi1, kombinasi2 FROM `tb_confidence` ORDER BY confidence DESC LIMIT 10";


        return $this->db->query($query)->result_array();
    }

 }

 ?>