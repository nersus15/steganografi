<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Buku extends CI_Model{
    function get_dt(){
        /** @var Datatables */
        $this->load->library('Datatables');
        $query = $this->db->from('buku_terbitan');

        if(!empty($filter)){
            extract($filter);
            if(isset($jenis))
                $query->where('parrent_element', $jenis);
            if(isset($level))
                $query->where('lvl', $level);


            if(isset($dt) && $dt == 0){
                $query->select('buku_terbitan.*');
                $compiledData = $query->get()->result();
                response(['data' => $compiledData]);

            }
        }        
        $header = array(
            'id' => array('searchable' => true),
            'judul' => array('searchable' => true),
            'penulis' => array('searchable' => true),
            'isbn' => array('searchable' => true),
            'panjang' => array('searchable' => true),
            'lebar' => array('searchable' => true),
            'harga' => array('searchable' => true),
            'halaman' => array('searchable' => true),
            'deskripsi' => ['searchable' => false],
            'cover' => ['searchable' => false],
        );
        

        $this->datatables->setHeader($header)
            ->setQuery($query);
        $data =  $this->datatables->getData();
        return $data;
    }

    function get_by($filters = []){
        $q = $this->db->select('*')->from('buku_terbitan');
		if(!empty($filters)){
            foreach($filters as $k => $v){
                if(is_numeric($k)){
                    $q->where($v, null, false);
                }else{
                    $q->where($k, $v);
                }
				
			}
		}
				
		return $q->get()->result_array();
    }

    function simpan($post, $edit = false){
        $input = fieldmapping('buku', $post, array(
            'judul' => '#unset',
            'penulis' => '#unset',
            'isbn' => '#unset',
            'panjang' => '#unset',
            'lebar' => '#unset',
            'halaman' => '#unset',
            'harga' => '#unset',
            'desc' => '#unset',
            'isbn' => '#unset',
        ));

        if(isset($_FILES['cover'])){
            $this->load->helper('file_upload');
            $input['cover'] = uploadImage($_FILES, 'cover', 'buku');
        }
        try {
            if($edit){
                if(!empty($post['old_cover']) && isset($input['cover']) && !empty($input['cover'])){
                    delete_img($post['old_cover'], 'buku');
                }

                $this->db->where('id', $post['id'])->update('buku_terbitan', $input);
            }else{
                $input['id'] = random(8);
                $input['ditambah'] = waktu();
                $this->db->insert('buku_terbitan', $input);
            }
            return [true, null];
        } catch (\Throwable $th) {
            //throw $th;
            return [false, $th->getMessage()];
        }
    }
    function delete($ids){
        try {
            $images = $this->db->select('cover')->where('cover IS NOT NULL', null)->where_in('id', $ids)->get('buku_terbitan')->result();
            $this->db->where_in('id', $ids)->delete('buku_terbitan');
            
            foreach($images as $row){
                delete_img($row->cover, 'buku');
            }
            
            return [true, null];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }

}