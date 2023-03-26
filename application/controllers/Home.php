<?php

use PhpOffice\PhpWord\PhpWord;

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller{

    function index(){
        $data = [
            'resource' => array('main', 'dore', 'form'),
            'content' => array('pages/dashboard'),
            'data_content' => array(
            ),
            // 'pageName' => 'Main Menu',
            // 'navbar' => 'component/navbar/navbar.dore',
            // 'adaThemeSelector' => true,
            'loading_animation' => true,
            // 'navbarConf' => array(
            //     'adaUserMenu' => true,
            //     'adaNotif' => true,
            //     'pencarian' => false,
            //     'adaSidebar' => true,
            //     'homePath' => base_url()
            // )
        ];

        $this->add_cachedJavascript('pages/dashboard');
        
        $this->addViews('template/dore', $data);
        $this->render();   
    }

    function test(){
        $name = 'test.docx';
        $text = "\r\nNO BAGIAN TANDA TANGAN\r\n1.\r\n2.\r\n3.\r\n4.\r\n5.\r\n6.\r\n7.\r\n8.\r\n9. KEUANGAN\r\nADM. KEU\r\nSERTIFIKAT\r\nBEASISWA\r\nAKADEMIK (NILAI)\r\nLABORATORIUM\r\nPERPUSTAKAAN\r\n        - BUKU\r\n        - TP/TA/SKRIPSI\r\nPEMBIMBING\r\nKETUA PRODI\r\n(CD)\r\nWAKIL  REKTOR III\r\n(SERTIFIKAT OPPS)\r\nPUSTIK (Repository)\r\nLPPM (Cek Plagiarisme, Artikel Siap Terbit)\r\n  1.___________________\r\n                                       \r\n  2. ___________________\r\n3.___________________\r\n                                          \r\n                                          4. ___________________\r\n5. ____________________\r\n                                  \r\n                                              6. _________________\r\n7. ____________________                       \r\n                                             8. _________________\r\n             \r\n13398590170009. \r\n\r\n";
        file_put_contents(get_path(DOCS_PATH . 'tmp/docs/' . $name), $text);

        $name = urldecode($name);
        $tmp = explode(".", $name);
        $ext = end($tmp);

        if (!in_array($ext, ['mp4', 'mkv']))
            $fpath = get_path(DOCS_PATH . 'tmp/docs/' . $name);
        else
            $fpath = get_path(DOCS_PATH . 'tmp/video/' . $name);
        $this->load->helper('download');
        force_download($name, $text);
    }

    function profile(){
        if(!is_login()){
            redirect(base_url());
        }
        $data = [
            'resource' => array('main', 'dore', 'form'),
            'content' => array('pages/profile'),
            'data_content' => array(
                'user' => sessiondata()
            ),
            'navbar' => 'component/navbar/navbar.dore',
            'adaThemeSelector' => true,
            'loadingAnim' => true,
            // 'pageName' => 'Profile',
            'pageName' => "<a href='". base_url(is_login('member') ? 'member' : 'dashboard') ."'> <i class='simple-icon-arrow-left'>Kembali</i> </a>",
            'navbarConf' => array(
                'adaUserMenu' => true,
                'adaNotif' => true,
                'pencarian' => false,
                'adaSidebar' => true,
                'homePath' => base_url()
            )
        ];

        $this->add_javascript('vendor/lightbox/js/lightbox.min', 'body:end', 'file');
        $this->add_stylesheet('vendor/lightbox/css/lightbox.min','head','file');
        $this->add_cachedStylesheet(
            ".section.footer{
                background: transparent;
                z-index: 99;
                width: 100%;
                margin-top: 39px;
                margin-bottom: 0px;
                bottom: 100%;
                position: static;
                text-align: center;
            }
            .separator{
                border-top: solid 1px darkgrey;
            }"
        , 'inline', 'head');
        
        $this->add_cachedStylesheet('pages/profile');
        $this->add_javascript('js/pages/profile', 'body:end');
        
        $this->addViews('template/dore', $data);
        $this->render();
    }
    
    function phpinfo(){
        phpinfo(INFO_ALL);
    }
}
