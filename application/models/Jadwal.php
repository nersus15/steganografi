<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal extends CI_Model{
    function get_all(){
        /** @var Datatables */
        $this->load->library('Datatables');
        $q = $this->db->from('jadwal')->join('lapangan', 'lapangan.id = jadwal.lapangan');

        $this->datatables->setHeader(array(
            'id' => array('searchable' => false, 'field' => 'jadwal.id'),
            'mulai' => array('searchable' => true),
            'selesai' => array('searchable' => true),
            'lapangan' => array('searchable' => true, 'field' => 'jadwal.lapangan'),
            'tarif' => array('searhcable' => true),
            'jenis' => array('searchable' => true, 'field' => 'lapangan.jenis'),
            'tempat' => array('searchable' => true, 'field' => 'lapangan.tempat')
        ));
        $this->datatables->setQuery($q);
        $data = $this->datatables->getData();
        return $data;
    }

    function get_last(){
        $lapangan = $this->db->select('*')->order_by('id', 'DESC')->get('jadwal')->result();
        return $lapangan;
    }

    function create($data){
        try {
            $id = random(8);
            $this->db->insert('jadwal', array('id' => $id, 'lapangan' => $data['lapangan'], 'mulai' => $data['mulai'], 'selesai' => $data['selesai'], 'tarif' => $data['tarif']));
            return [true, 'Berhasil menambah data jadwal'];
        } catch (\Throwable $th) {
            return [false, "Gagal Menambah data jadwal " . print_r($th, true)];
        }
    }

    function update($data){
        try {
            $this->db->where('id', $data['id'])->update('jadwal', array('lapangan' => $data['lapangan'], 'mulai' => $data['mulai'], 'selesai' => $data['selesai'], 'tarif' => $data['tarif']));
            return [true, 'Berhasil update data jadwal #' . $data['id']];
        } catch (\Throwable $th) {
            return [false, "Gagal update data lapangan " . print_r($th, true)];
        }
    }

    function delete($ids){
        try {
            $this->db->where_in('id', $ids)->delete('jadwal');
            return [true, "Berhasil hapus data"];
        } catch (\Throwable $th) {
            return [false, "Gagal Menghapus Data jadwal"];
        }
    }
}