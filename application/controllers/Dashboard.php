<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    function index(){
        if(!is_login('admin') && !is_login('pimpinan'))
            redirect(base_url());
        else
            redirect(base_url('dashboard/' . sessiondata('login', 'role')));
    }
    function admin()
    {
        if(!is_login('admin'))
            redirect(base_url());

        $dataBooking = $this->db->select('booking.*')->get('booking')->result();
        $data_member = $this->db->select('member.*')->get('member')->result();
        $data_lapangan = $this->db->select('lapangan.*')->get('lapangan')->result();
        $data_jadwal = $this->db->select('jadwal.*')->get('jadwal')->result();
        $data = [
            'resource' => array('main', 'dore'),
            'content' => array('pages/dashboard/admin'),
            'data_content' => array(
                'jadwal' => $data_jadwal,
                'lapangan' => $data_lapangan,
                'booking' => $dataBooking,
                'member' => $data_member,
            ),
            'navbar' => 'component/navbar/navbar.dore',
            'adaThemeSelector' => true,
            'sidebar' => 'component/sidebar/sidebar.dore',
            'pageName' => 'Dashboard',
            'sidebarConf' => config_sidebar('comp', 'admin', 0),
            'navbarConf' => array(
                'adaUserMenu' => true,
                'adaNotif' => true,
                'pencarian' => false,
                'adaSidebar' => true,
                'homePath' => base_url()
            )
        ];
        $this->addViews('template/dore', $data);
        $this->render();
    }

    function pimpinan(){
        if(!is_login('pimpinan'))
            redirect(base_url());
            
            $dataBooking = $this->db->select('booking.*')->get('booking')->result();
            $data_member = $this->db->select('member.*')->get('member')->result();
            $data_admin = $this->db->select('user.*')->where('role', 'admin')->get('user')->result();
            $data_lapangan = $this->db->select('lapangan.*')->get('lapangan')->result();
            $data_jadwal = $this->db->select('jadwal.*')->get('jadwal')->result();
            $data = [
                'resource' => array('main', 'dore'),
                'content' => array('pages/dashboard/pimpinan'),
                'data_content' => array(
                    'jadwal' => $data_jadwal,
                    'lapangan' => $data_lapangan,
                    'booking' => $dataBooking,
                    'admin' => $data_admin,
                    'member' => $data_member,
                ),
                'navbar' => 'component/navbar/navbar.dore',
                'adaThemeSelector' => true,
                'sidebar' => 'component/sidebar/sidebar.dore',
                'pageName' => 'Dashboard',
                'sidebarConf' => config_sidebar('comp', 'pimpinan', 0),
                'navbarConf' => array(
                    'adaUserMenu' => true,
                    'adaNotif' => true,
                    'pencarian' => false,
                    'adaSidebar' => true,
                    'homePath' => base_url()
                )
            ];
        $this->addViews('template/dore', $data);
        $this->render();
    }
}
