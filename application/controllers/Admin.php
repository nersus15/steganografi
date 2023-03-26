<?php


defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends CI_Controller
{
    function index(){
        $data = [
            'resource' => array('main', 'dore','datatables', 'form'),
            'contentHtml' => array(),
            'content' => array(),
            'navbar' => 'component/navbar/navbar.dore',
            'sidebar' => 'component/sidebar/sidebar.dore',
            'pageName' => 'Dashboard',
            'sidebarConf' => config_sidebar('comp', 'def', 0),
            'navbarConf' => array(
                'adaUserMenu' => true,
                'adaNotif' => false,
                'pencarian' => false,
                'adaSidebar' => true,
                'homePath' => base_url()
            ),
            'bodyClass' => 'menu-hidden sub-hidden'
        ];
        $this->addViews('template/dore', $data);
        $this->render();
    }
    function buku($id = null){
        if(!is_login())
            redirect(base_url());

        if(empty($id)) $this->terbitan();
        else $this->detail($id);
    }
    function login(){
        if(is_login())
            redirect(base_url('admin/buku'));
        $ran = random(50);
        $this->session->set_userdata('ran', $ran);
        $data = array(
            'resource' => array('main', 'form', 'login'),
            'content' => array('forms/login2'),
            'hideSpinner' => true,
            'loading_animation' => true,
        );
        $this->add_cachedJavascript('pages/auth', 'file', 'body:end', array(
            'formid' => '#form-login',
            'ran' => $ran
        ));
        $this->removeFromResourceGroup('main', 'vendor/fontawesome/css/all.min.css');
        $this->addViews('template/blank', $data);
        $this->render();
    }

    private function terbitan(){
        if(!is_login())
            redirect(base_url());

       
        $tabelBuku = $this->getContentView('component/datatables/datatables.responsive', array(
            'dtTitle' => '',
            'dtid' => 'dt-buku-terbitan',
            'head' => array(
            '', 'Cover', 'Judul','Penulis', 'ISBN', 'Deskripsi', 'Dimensi', 'Halaman', 'Harga'
            ),
            'skrip' => 'dtconfig/dt_buku', //wajib
            'skrip_data' => array('id' => 'dt-buku-terbitan'),
            'options' => array(
                'source' => 'ws/buku',
                'search' => 'false',
                'select' => 'multi', //false, true, multi
                'checkbox' => 'true',
                'change' => 'false',
                'dom' => 'rtip',
                'responsive' => 'true',
                'auto-refresh' => 'false',
                'deselect-on-refresh' => 'true',
            ),
            'form' => array(
                'id' => 'form-admin',
                'path' => '',
                'nama' => 'Form Admin',
                'skrip' => 'forms/form_admin',
                'formGenerate' => array(
                    [
                        'type' => 'hidden', 'name' => '_http_method', 'id' => 'method',
                    ],
                    [
                        'type' => 'hidden', 'name' => 'old_cover', 'id' => 'old-cover',
                    ],
                    [
                        'type' => 'hidden', 'name' => 'id', 'id' => 'id'
                    ],
                    [
                        "label" => 'Judul', "placeholder" => 'Judul Buku',
                        "type" => 'text', "name" => 'judul', "id" => 'judul', 'attr' => 'data-rule-required="true"'
                    ],
                    [
                        "label" => 'Penulis', "placeholder" => 'Nama Penulis',
                        "type" => 'text', "name" => 'penulis', "id" => 'penulis', 'attr' => 'data-rule-required="true"'
                    ],
                    [
                        "label" => 'ISBN (Jika ada)', "placeholder" => 'ISBN',
                        "type" => 'text', "name" => 'isbn', "id" => 'isbn',
                    ],
                    [
                        "label" => 'Panjang (cm)', "placeholder" => 'Panjang Buku (dalam cm)',
                        "type" => 'text', "name" => 'panjang', "id" => 'panjang', 'attr' =>  'data-rule-number="true"'
                    ],
                    [
                        "label" => 'Lebar (cm)', "placeholder" => 'Lebar Buku (dalam cm)',
                        "type" => 'text', "name" => 'lebar', "id" => 'lebar', 'attr' =>  'data-rule-number="true"'
                    ],
                    [
                        "label" => 'Halaman', "placeholder" => 'Jumlah Halaman',
                        "type" => 'text', "name" => 'halaman', "id" => 'halaman', 'attr' =>  'data-rule-number="true"'
                    ],
                    [
                        "label" => 'Harga', "placeholder" => 'Harga Buku',
                        "type" => 'text', "name" => 'harga', "id" => 'harga', 'attr' =>  'data-rule-number="true"'
                    ],
                    [
                        "label" => 'Sinopsis/ Deskripsi', "placeholder" => 'Sinopsis/ Deskripsi Buku',
                        "type" => 'textarea', "name" => 'desc', "id" => 'desc',
                    ],
                    [
                        "label" => 'Cover (gambar)', "placeholder" => 'Gambar Cover Buku',
                        "type" => 'file', "name" => 'cover', "id" => 'cover', 'class' => 'dropzone'
                    ],
                    
                    
                ),
                    'posturl' => 'ws/buku',
                    'buttons' => array(
                        [ "type" => 'reset', "data" => 'data-dismiss="modal"', "text" => 'Batal', "id" => "batal", "class" => "btn btn btn-warning" ],
                        [ "type" => 'submit', "text" => 'Simpan', "id" => "simpan", "class" => "btn btn btn-primary" ]
                )
            ),
            'data_panel' => array(
                'nama' => 'dt-buku-terbitan',
                'perpage' => 10,
                'pages' => array(1, 2, 10),
                'hilangkan_display_length' => true,
                'toolbar' => array(
                    array(
                        'tipe' => 'buttonset',
                        'tombol' => array(
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Tambah', 'icon' => 'icon-plus simple-icon-paper-plane', 'class' => 'btn-outline-primary tool-add tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Update', 'icon' => 'icon-plus simple-icon-pencil', 'class' => 'btn-outline-warning tool-edit tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Hapus', 'icon' => 'icon-delete simple-icon-trash', 'class' => 'btn-outline-danger tool-delete tetap'),
                        )
                    ),
                ),
            )
        ), true);

        $data = [
            'resource' => array('main', 'dore','datatables', 'form'),
            'content' => array(),
            'contentHtml' => array($tabelBuku),
            'navbar' => 'component/navbar/navbar.dore',
            'sidebar' => 'component/sidebar/sidebar.dore',
            'pageName' => 'Data',
            'subPageName' => 'Buku Terbitan',
            'sidebarConf' => config_sidebar('comp', 'def', 1),
            'navbarConf' => array(
                'adaUserMenu' => true,
                'adaNotif' => false,
                'pencarian' => false,
                'adaSidebar' => true,
                'homePath' => base_url()
            ),
            'bodyClass' => 'menu-hidden sub-hidden'
        ];
        $this->add_javascript('vendor/dropzone/js/dropzone.min');
        $this->add_stylesheet('vendor/dropzone/css/dropzone.min');

        $this->addViews('template/dore', $data);
        $this->render();
    }
    private function detail($id){

    }
}
