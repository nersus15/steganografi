<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Model{
    function get_lastid(){
        $member = $this->db->select('id')->order_by('id', 'DESC');
        $member = $member->get('member')->row();
        if(empty($member)) return 'MBR0';
        return $member->id;
    }
    function get_all(){
        $this->load->library('Datatables');
        $q = $this->db->from('member')->join('user', 'user.member = member.id');

        $this->datatables->setHeader(array(
            'userid' => array('searchable' => true, 'field' => 'user.id'),
            'id' => array('searchable' => true, 'field' => 'member.id'),
            'username' => array('searchable' => true),
            'tim' => array('searchable' => true),
            'hp' => array('searchable' => true),
            'email' => array('searchable' => true),
            'penanggung_jawab' => array('searchable' => true,),
            'asal' => array('searhcable' => true),
            'dibuat' => array('searchable' => true),
        ));
        // $this->datatables->addSelect('member.*, user.username, user.id as userid, user.');
        $this->datatables->setQuery($q);
        $data = $this->datatables->getData();
        return $data;
    }
    function get_by($where = null){
        $query = $this->db->from('member')->join('user', 'user.member = member.id')
            ->select('member.*, user.username, user.id as userid, user.hp, user.email');
        if(!empty($where)){
            foreach($where as $k => $v){
                $query->where($k, $v);
            }
        }

        return $query->get()->result();
    }
    function create($data){
        try {
            $dataMember = array(
                'id' => $data['id'],
                'tim' => $data['tim'],
                'penanggung_jawab' => $data['wakil'],
                'asal' => $data['alamat'],
                'dibuat' => waktu()
            );
            $dataUser = array(
                'id' => random(8),
                'nama' => $data['wakil'],
                'username' => $data['username'],
                'password' => password_hash($dataMember['id'] . str_replace('-', '', waktu(null, MYSQL_DATE_FORMAT)), PASSWORD_DEFAULT),
                'role' => 'member',
                'member' => $dataMember['id']
            );
            if(isset($data['email']) && !empty($data['email']))
                $dataUser['email'] = $data['email'];
            if(isset($data['hp']) && !empty($data['hp']))
                $dataUser['hp'] = $data['hp'];

            $this->db->insert('member', $dataMember);
            $this->db->insert('user', $dataUser);

        } catch (\Throwable $th) {
            return [false, "Gagal menambah member " . print_r($th, true)];
        }
        return array(true, "Berhasil menambah member");
    }

    function update($data){
        try {
            $dataMember = array(
                'tim' => $data['tim'],
                'penanggung_jawab' => $data['wakil'],
                'asal' => $data['alamat'],
                'dibuat' => waktu()
            );
            $dataUser = array(
                'nama' => $data['wakil'],
            );
            if(isset($data['email']) && !empty($data['email']))
                $dataUser['email'] = $data['email'];
            if(isset($data['hp']) && !empty($data['hp']))
                $dataUser['hp'] = $data['hp'];

            $this->db->where('id', $data['id'])->update('member', $dataMember);
            $this->db->where('id', $data['uid'])->update('user', $dataUser);
        } catch (\Throwable $th) {
            return array(false, "Gagal update member " . print_r($th, true));
        }
        return array(true, "Berhasil update member");
    }

    function delete($ids){
        try {
            $this->db->where_in('id', $ids)->delete('member');
            $this->db->where_in('member', $ids)->delete('user');
        } catch (\Throwable $th) {
            return array(false, "Gagal Menghapus Data member" . print_r($th, true));
        }
        return array(true, "Berhasil Hapus data");
    }
}