<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
    function booking($headless = 0)
    {
        if(is_login('member'))
            redirect(base_url());
        elseif(is_login('admin'))
            redirect(base_url('dashboard'));
            
        $tabelBooking = $this->getContentView('component/datatables/datatables.responsive', array(
            'dtTitle' => 'Data bookingan',
            'dtid' => 'dt-booking',
            'head' => array(
               '', 'Lapangan', 'Jadwal', 'Nama Tim','Perwakilan', 'Tanggal Booking', 'Member', 'Tarif', 'Diskon', 'Tagihan', 'Status', 'Bukti Bayar'
            ),
            'skrip' => 'dtconfig/dt_booking', //wajib
            'skrip_data' => array('id' => 'dt-booking'),
            'options' => array(
                'source' => 'ws/get_booking',
                'search' => 'false',
                'select' => 'multi', //false, true, multi
                'checkbox' => 'true',
                'change' => 'false',
                'dom' => 'rtip',
                'responsive' => 'false',
                'auto-refresh' => '10000',
                'deselect-on-refresh' => 'false',
            ),
            'data_panel' => array(
                'nama' => 'dt-booking',
                'perpage' => 10,
                'pages' => array(1, 2, 10),
                'hilangkan_display_length' => true,
                'toolbar' => array(
                    array(
                        'tipe' => 'buttonset',
                        'tombol' => array(
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Export Pdf', 'icon' => 'icon-plus simple-icon-paper-plane', 'class' => 'btn-outline-primary tool-export-pdf tetap'),
                        )
                    ),
                ),
                'toolbarSkrip' => 'pages/bookinglist',
            )
        ), true);
        $data = [
            'resource' => array('main', 'dore','datatables', 'form'),
            'contentHtml' => array($tabelBooking),
            'content' => array(),
            'navbar' => 'component/navbar/navbar.dore',
            'sidebar' => 'component/sidebar/sidebar.dore',
            'pageName' => 'Report',
            'subPageName' => 'Booking List',
            'sidebarConf' => config_sidebar('comp', 'pimpinan', 0, array('sub' => 0, 'menu' => 0)),
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

    function member(){
        $tabelMember = $this->getContentView('component/datatables/datatables.responsive', array(
            'dtTitle' => 'Daftar Member',
            'dtid' => 'dt-member',
            'head' => array(
               '', 'Nama Tim','Perwakilan', 'No. HP', 'Email', 'Alamat', 'Tanggal Daftar', 'Username', 'Password'
            ),
            'skrip' => 'dtconfig/dt_member', //wajib
            'skrip_data' => array('id' => 'dt-member'),
            'options' => array(
                'source' => 'ws/get_member',
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
                'id' => 'form-member',
                'path' => '',
                'nama' => 'Form member',
                'skrip' => 'forms/form-member',
            ),
            'data_panel' => array(
                'nama' => 'dt-member',
                'perpage' => 10,
                'pages' => array(1, 2, 10),
                'hilangkan_display_length' => true,
                'toolbar' => array(
                    array(
                        'tipe' => 'buttonset',
                        'tombol' => array(
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Export Pdf', 'icon' => 'icon-plus simple-icon-paper-plane', 'class' => 'btn-outline-primary tool-export-pdf tetap'),
                        )
                    ),
                ),
                'toolbarSkrip' => 'pages/memberlist',
            )
        ), true);
        $data = [
            'resource' => array('main', 'dore','datatables', 'form'),
            'contentHtml' => array($tabelMember),
            'content' => array(),
            'navbar' => 'component/navbar/navbar.dore',
            'sidebar' => 'component/sidebar/sidebar.dore',
            'pageName' => 'Report',
            'subPageName' => 'Data Member',
            'sidebarConf' => config_sidebar('comp', 'pimpinan', 0, array('sub' => 0, 'menu' => 1)),
            'navbarConf' => array(
                'adaUserMenu' => true,
                'adaNotif' => true,
                'pencarian' => false,
                'adaSidebar' => true,
                'homePath' => base_url()
            ),
            'bodyClass' => 'menu-hidden sub-hidden'
        ];
        $this->addViews('template/dore', $data);
        $this->render();
    }
}
