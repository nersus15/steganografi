<?php
class Notification {
    public $ci;
    public function __construct() {
        $this->ci =& get_instance();
        $tmp = [];
        $q = $this->ci->db->select('*')
            ->from('notifikasi');

        if(is_login()){
            $q->where("(jenis = 'global' and role = '". sessiondata('login', 'role') ."')")
                ->or_where("(jenis = 'personal' and user='" . sessiondata('login', 'id') . "')");
        }
        $tmp = $q->get()->result(); 
        $this->ci->notifikasi = $tmp;
            
    }

    function create($data, $batch = false){
        foreach($data as $d){
            $this->ci->db->insert('notifikasi', $d);
        }
    }

    function baca($nid){
        $dibaca = waktu();
        $this->ci->db->where('id', $nid)->update('notifikasi', ['dibaca' => $dibaca]);
        response(['dibaca' => $dibaca]);
    }
}