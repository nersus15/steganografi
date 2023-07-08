<?php

use PhpOffice\PhpWord\PhpWord;


defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    /** @var Word */
    var $word;

    public function __construct()
    {
        parent::__construct();
    }
    function index()
    {
        $data = [
            'resource' => array('main', 'dore', 'form'),
            'content' => array('pages/dashboard'),
            'data_content' => array(),

            'loading_animation' => true,
        ];
        if (is_login()) {
            $data += array(
                'navbar' => 'component/navbar/navbar.dore',
                'navbarConf' => array(
                    'adaUserMenu' => true,
                    'adaNotif' => false,
                    'pencarian' => false,
                    'adaSidebar' => false,
                    'homePath' => base_url()
                ),
            );
        }

        $this->add_cachedJavascript('pages/dashboard', 'file', 'body:end', [
            'isLogin' => is_login()
        ]);
        $this->addViews('template/dore', $data);
        $this->render();
    }

    function test()
    {
        $this->load->library('Word');
        $fpath = get_path(DOCS_PATH . 'tmp/docs/');

        $PHPWord = $this->word; // New Word Document
        $section = $PHPWord->createSection(); // New portrait section
        // Add text elements
        $section->addText('Hello World!');
        $section->addTextBreak(2);
        $section->addText('Mohammad Rifqi Sucahyo.', array('name' => 'Verdana', 'color' => '006699'));
        $section->addTextBreak(2);
        $PHPWord->addFontStyle('rStyle', array('bold' => true, 'italic' => true, 'size' => 16));
        $PHPWord->addParagraphStyle('pStyle', array('align' => 'center', 'spaceAfter' => 100));
        // Save File / Download (Download dialog, prompt user to save or simply open it)
        $section->addText('Ini Adalah Demo PHPWord untuk CI', 'rStyle', 'pStyle');

        $filename = 'dadada.docx';
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save($fpath . $filename);
    }

    function profile()
    {
        if (!is_login()) {
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
            'pageName' => "<a href='" . base_url() . "'> <i class='simple-icon-arrow-left'>Kembali</i> </a>",
            'navbarConf' => array(
                'adaUserMenu' => true,
                'adaNotif' => true,
                'pencarian' => false,
                'adaSidebar' => true,
                'homePath' => base_url()
            )
        ];

        $this->add_javascript('vendor/lightbox/js/lightbox.min', 'body:end', 'file');
        $this->add_stylesheet('vendor/lightbox/css/lightbox.min', 'head', 'file');
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
            }",
            'inline',
            'head'
        );

        $this->add_cachedStylesheet('pages/profile');
        $this->add_javascript('js/pages/profile', 'body:end');

        $this->addViews('template/dore', $data);
        $this->render();
    }

    function phpinfo()
    {
        phpinfo(INFO_ALL);
    }
}
