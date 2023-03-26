<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Booking extends CI_Model{
    function get_all($member = null){
        /** @var Datatables */
        $this->load->library('Datatables');
        $q = $this->db->from('booking')
            ->join('lapangan', 'lapangan.id = booking.lapangan', 'inner')
            ->join('jadwal', 'jadwal.id = booking.jadwal', 'inner')
            ->join('file_upload', 'file_upload.id = booking.bukti_bayar', 'left')
            ->join('member', 'member.id = booking.member', 'left');

        if(!empty($member))
            $q->where('booking.member', $member);
    
        $this->datatables->setHeader(array(
            'id' => array('searchable' => true, 'field' => 'booking.id'),
            'lapangan' => array('searchable' => false, 'field' => 'booking.lapangan'),
            'tanggal' => array('searchable' => false, 'field' => 'booking.tanggal'),
            'status' => array('searchable' => false, 'field' => 'booking.status'),
            'wakil' => array('searchable' => true, 'field' => 'booking.penanggung_jawab'),
            'tim' => array('searchable' => true, 'field' => 'booking.tim'),
            'diskon' => array('searchable' => true, 'field' => 'booking.diskon'),
            'dibuat' => array('searchable' => true, 'field' => 'booking.dibuat'),
            'bukti_bayar' => array('searchable' => false, 'field' => 'file_upload.nama'),
            'mulai' => array('searchable' => true),
            'selesai' => array('searchable' => true),
            'tarif' => array('searhcable' => true),
            'jenis' => array('searchable' => true, 'field' => 'lapangan.jenis'),
            'tempat' => array('searchable' => true, 'field' => 'lapangan.tempat'),
            'mid' => array('searchable' => true, 'field' => 'member.id'),
            'mtim' => array('searchable' => true, 'field' => 'member.tim'),
            'mwakil' => array('searchable' => true, 'field' => 'member.penanggung_jawab'),
            'masal' => array('searchable' => true, 'field' => 'member.asal'),
            'registrar' => array('searchable' => true),
            'jadwal' => array('serchable' => false, 'field' => 'jadwal.id')
        ));
        $this->datatables->setQuery($q);
        $data = $this->datatables->getData();
        return $data;
    }
    function my_history($mid){
    
    }
    function get_last(){
        $lapangan = $this->db->select('*')->order_by('id', 'DESC')->get('jadwal')->result();
        return $lapangan;
    }
    function get_by($where = null){
        $query = $this->db->select('booking.*, jadwal.id as idjadwal, jadwal.mulai, jadwal.selesai, jadwal.tarif, lapangan.jenis, lapangan.tempat, member.id as mid, member.tim  as mtim, member.penanggung_jawab as mwakil, member.asal as masal')
                ->from('booking')
                ->join('lapangan', 'lapangan.id = booking.lapangan', 'inner')
                ->join('jadwal', 'jadwal.id = booking.jadwal', 'inner')
                ->join('member', 'member.id = booking.member', 'left');

        if(!empty($where)){
            foreach($where as $k => $v){
                $query->where($k, $v);
            }
        }

        return $query->get()->result();
    }
    function create($data, $member = null){
        try {
            $lastid = $this->db->select('id')->order_by('id', 'DESC')->get('booking')->row();
            if(!empty($lastid)){
                $lastid = intval(substr($lastid->id, 12, 2));
                $lastid = strlen($lastid) > 1 || $lastid == 9 ? ++$lastid : '0' . ++$lastid;
            }else{
                $lastid = "01";
            }
            if(isset($data['member']) && !empty($data['member'])){
                $tmp = $this->db->select('*')
                    ->where('MONTH(dibuat)', waktu('', 'm'))
                    ->where('member', $data['member'])
                    ->get('booking')->result();
                if(count($tmp) < 3)
                    $data['diskon'] = 30;
            }
            $data['id'] = "BOOK" . str_replace('-', '', waktu(null, MYSQL_DATE_FORMAT)) . $lastid;
            $data['dibuat'] = waktu();
            $data['status'] = "baru";
            $this->db->insert('booking', $data);
            

            return[true, "Berhasil membooking", $data];
        } catch (\Throwable $th) {
            return[true, "Berhasil membooking", $data];
        }
    }

    function update($data, $ids){
        try {
            $this->db->where_in('id', $ids)->update('booking', $data);
            return[true, "Berhasil update data", $data];
        } catch (\Throwable $th) {
            return[true, "Berhasil update data", $data];
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