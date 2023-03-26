

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lapangan extends CI_Model{
    function get_lastid(){
        $lapangan = $this->db->select('id')->order_by('id', 'DESC');
        $lapangan = $lapangan->get('lapangan')->row();
        if(empty($lapangan)) return 'LPN0';
        return $lapangan->id;
    }
    function get_all(){
        /** @var Datatables */
        $this->load->library('Datatables');


        $query = $this->db->from('lapangan');

        if(!empty($filter)){
            extract($filter);
            if(isset($jenis))
                $query->where('parrent_element', $jenis);
            if(isset($level))
                $query->where('lvl', $level);


            if(isset($dt) && $dt == 0){
                $query->select('lapangan.*');
                $compiledData = $query->get()->result();
                response(['data' => $compiledData]);

            }
        }        
        $header = array(
            'id' => array('searchable' => true),
            'jenis' => array('searchable' => true),
            'tempat' => array('searchable' => true),
        );
        

        $this->datatables->setHeader($header)
            ->addSelect('*')
            ->setQuery($query);
        $data =  $this->datatables->getData();
        return $data;
    }

    function get_last(){
        $lapangan = $this->db->select('*')->order_by('id', 'DESC')->get('lapangan')->result(); 
        return $lapangan;
    }

    function create($data){
        try {
            $this->db->insert('lapangan', array('id' => $data['id'], 'jenis' => $data['jenis'], 'tempat' => $data['tempat']));
            return [true, 'Berhasil menambah data lapangan'];
        } catch (\Throwable $th) {
            return [false, "Gagal Menambah data lapangan " . print_r($th, true)];
        }
    }

    function update($data){
        try {
            $this->db->where('id', $data['id'])->update('lapangan', array('jenis' => $data['jenis'], 'tempat' => $data['tempat']));
            return [true, 'Berhasil update data lapangan #' . $data['id']];
        } catch (\Throwable $th) {
            return [false, "Gagal update data lapangan " . print_r($th, true)];
        }
    }

    function delete($ids){
        try {
            $this->db->where_in('id', $ids)->delete('lapangan');
            return [true, "Berhasil hapus data"];
        } catch (\Throwable $th) {
            return [false, "Gagal Menghapus Data Lapangan terdaftar di jadwal"];
        }
    }
}