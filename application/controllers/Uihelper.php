<?php defined('BASEPATH') or exit('No direct script access allowed');
class Uihelper extends CI_Controller{
    function form()
    {

        if (httpmethod())
            response(['message' => 'Ilegal akses'], 403);

        if (!isset($_GET['f']))
            response(['message' => 'File (form) kosong'], 404);
        $skrip = '';
        if(isset($_GET['s']) && !empty($_GET['s']))
            $skrip = $_GET['s'];
            
        $form = $_GET['f'];
        $data = array(
            'ed' => [],
            'sv' => []
        );
        if(isset($_GET['ed']))
            $data['ed'] = json_decode($_GET['ed']);
        if(isset($_GET['sv']))
            $data['sv'] = json_decode($_GET['sv']);

        if (!file_exists(APPPATH . 'views/' . $form . '.php'))
            response(['message' => 'Form ' . $form . ' Tidak ditemukan'], 404);
        else {
            $html =  $this->load->view($form, $data, true);
            
            if(!empty($skrip)){
                $skrip = load_script($skrip, [
                    'form_cache' => json_encode($data['ed']),
                    'form_data' => json_encode($data['sv'])
                ], true);    
            }
            response([
                'html' => $html . "<script id='" .$data['sv']->skripid. "'>" . $skrip . '</script>'
            ]);
        }
    }
    function skrip()
    {
        if (httpmethod())
            response(['message' => 'Ilegal akses'], 403);
            
        $skrip = '';
        if(isset($_GET['s']) && !empty($_GET['s']))
            $skrip = $_GET['s'];
        if(empty($skrip)) response(['skrip' => '']);

        if (!file_exists(ASSETS_PATH . 'js/' . $skrip . '.js'))
            response(['message' => 'Form ' . $skrip . ' Tidak ditemukan'], 404);
        else {
            $data = array(
                'ed' => [],
                'sv' => []
            );
            if(isset($_GET['ed']))
                $data['ed'] = json_decode($_GET['ed']);
            if(isset($_GET['sv']))
                $data['sv'] = json_decode($_GET['sv']);
            response([
                'skrip' => "<script id='". $data['sv']->skripid ."'>" . load_script($skrip,[
                    'form_cache' => json_encode($data['ed']),
                    'form_data' => json_encode($data['sv'])
                ], true) . "</script>"
            ]);
        }
    }

    function notifcenter(){
        response("OK");
    }

    function export($name, $jenis = 'pdf'){
        if($name == 'laporan_booking'){
            $this->load->model('Booking');
            $data = $this->Booking->get_all();
            $html = $this->siapkan_tabel_booking($data);
        }elseif($name == 'laporan_member'){
            $this->load->model('Member');
            $data = $this->Member->get_all();
            $html = $this->siapkan_tabel_member($data);
        }
        buat_pdf($html, kapitalize(str_replace('_', ' ', $name)) . '.' . $jenis);
        exit();
    }

    private function siapkan_tabel_booking($data){
        $html = $this->getContentView('report/tabel', array(
            'dtTitle' => 'Laporan Booking',
            'header' => array( 'Lapangan', 'Jadwal', 'Nama Tim','Perwakilan', 'Tanggal Booking', 'Member', 'Tarif', 'Diskon', 'Tagihan', 'Status'),
            'data' => $data->data, 
            'config' => array(
                function($data){
                    return $data['lapangan'] . " (" . $data['jenis'] . ") - " . $data['tempat'];
                },
                function($data){
                    return  $data['tanggal'] . " Jam " . $data['mulai'] . " - " . $data['selesai'];
                },
                function($data){
                    return $data['mtim'] ?? $data['tim'];
                },
                function($data){
                    return $data['mwakil'] ?? $data['wakil'];
                },
                'dibuat',
                function ($data){
                    return  $data['mid'] ?? "Bukan Member";
                },
                function($data){
                    return rupiah_format($data['tarif']);
                },
                function($data){
                    $data['diskon'] = $data['diskon'] ?? 0;
                    $diskon = ($data['tarif'] * $data['diskon'])/100;
                    return !empty($data['mid']) ? $data['diskon'] . '% - ' . rupiah_format($diskon) : '-';
                },
                function ($data){
                    $data['diskon'] = $data['diskon'] ?? 0;
                    $diskon = ($data['tarif'] * $data['diskon'])/100;
                    return !empty($data['mid']) ? rupiah_format($data['tarif'] - $diskon) : rupiah_format($data['tarif']);
                },
                'status',
            )
        ), true);
        return $html;
    }
    private function siapkan_tabel_member($data){
        $html = $this->getContentView('report/tabel', array(
            'dtTitle' => 'Laporan Booking',
            'header' => array( 'Nama Tim','Perwakilan', 'No. HP', 'Email', 'Alamat', 'Tanggal Daftar', 'Username'),
            'data' => $data->data, 
            'config' => array(
                'tim',
                'penanggung_jawab',
                'hp',
                'email',
                'asal',
                'dibuat',
                'username'
            )
        ), true);
        return $html;
    }
}