<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use Smalot\PdfParser\Parser;
use Xrsa\XRsa;

class Ws extends CI_Controller
{
    /** @var Authentication */
    var $authenticatioin;

    /** @var Word */
    var $word;

    /** @var Dompdf_gen */
    var $dompdf_gen;

    /** @var PDF2Text */
    var $pdf2text;

    /** @var Steganografi */
    var $Steganografi;

    /** @var ReadWriteDocs */
    var $readwritedocs;

    /** @var CI_Loader */
    var $load;

    /** @var CI_Input */
    var $input;

    /** @var Authentication */
    var $authentication;

    function login()
    {
        if (is_login())
            redirect('admin/login');
        $this->load->library('Authentication');

        if (!httpmethod())
            response("Metode akses ilegal");

        list($input) = $this->authentication->persiapan($_POST);
        $this->authentication->login($input);
    }
    function update_profile()
    {
        if (!httpmethod())
            response("Metode akses ilegal", 403);
        if (!is_login())
            response("Anda belum login", 403);

        $post = $_POST;
        $dataUser = fieldmapping('user', $post);
        if (is_login('member')) {
            $dataMember = fieldmapping('member', $_POST);
            $dataMember['penanggung_jawab'] = $dataUser['nama'];
            $dataUser['member'] = $post['memberid'];
        }

        if (isset($dataUser['password']) && !empty($dataUser['password']))
            $dataUser['password'] = password_hash($dataUser['password'], PASSWORD_DEFAULT);
        else
            unset($dataUser['password']);

        if (isset($_FILES['pp']) && !empty($_FILES['pp'])) {
            $this->load->helper('file_upload_helper');
            $fname = uploadImage($_FILES['pp'], 'pp', 'profile');

            if (sessiondata('login', 'photo') != 'default.jpg')
                delete_img(sessiondata('login', 'photo'));
            $dataUser['photo'] = $fname;
        }
        if (is_login('member'))
            $this->db->where('id', sessiondata('login', 'memberid'))->update('member', $dataMember);
        $this->db->where('id', sessiondata('login', 'id'))->update('user', $dataUser);

        // Update in localdata
        $tmp = sessiondata();
        if (is_login('member')) {
            foreach ($dataMember as $key => $value) {
                if (sessiondata('login', $key) != $value)
                    $tmp[$key] = $value;
            }
        }
        foreach ($dataUser as $key => $value) {
            if ($key == 'password') continue;
            if (sessiondata('login', $key) != $value)
                $tmp[$key] = $value;
        }
        $this->session->set_userdata('login', $tmp);
        response("Berhasil update profile");
    }

    function cek_username()
    {
        if (!httpmethod()) response("Ilegal Akses", 403);
        if (!is_login()) response("Anda belum login", 403);
        if (sessiondata('login', 'username') == $_POST['username']) response(['boleh' => true]);
        if (!isset($_POST['username']) || empty($_POST['username'])) response(['boleh' => false]);
        $usernameBaru = $_POST['username'];

        $user = $this->db->select('*')->where('username', $usernameBaru)->get('user')->result();
        if (!empty($user))
            response(['boleh' => false]);
        else
            response(['boleh' => true]);
    }
    function logout()
    {
        if (!is_login())
            response(['message' => 'Anda belum login', 'type' => 'error'], 401);

        try {
            $this->session->unset_userdata('login');
            response(['message' => 'Anda berhasil logout', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            response(['message' => 'Gagal, Terjadi kesalahan', 'type' => 'error', 'err' => $th], 500);
        }
    }

    function rsakey_post()
    {
        require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');
        $key = Xrsa::createKeys();
        $nama = random(8);


        // file_put_contents(get_path(DOCS_PATH . 'pkeys/' . $nama . '-private.key'), $key['private_key']);
        // file_put_contents(get_path(DOCS_PATH . 'pkeys/' . $nama . '-public.key'), $key['public_key']);

        response($key);
    }

    function key_get($nama)
    {
        $files = array($nama . '-private.key' => get_path(DOCS_PATH . 'pkeys/' . $nama . '-private.key'), $nama . '-public.key' => get_path(DOCS_PATH . 'pkeys/' . $nama . '-public.key'));
        $zipname = $nama . '.zip';
        $zip = new ZipArchive;
        $zip->open(get_path(DOCS_PATH . 'pkeys/' . $zipname), ZipArchive::CREATE);
        foreach ($files as $key => $file) {
            $zip->addFile($file, $key);
        }
        $zip->close();

        foreach ($files as $key => $file) {
            unlink($file);
        }
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
    }

    function enkripsi_post()
    {
        try {
            $this->load->helper('file_upload_helper');
            $this->load->model('Steganografi');

            $fpath = get_path(DOCS_PATH . 'tmp/docs/');
            $tmpName = uploadFile($_FILES, 'file', 'tmp');
            $public_key = $this->input->post('public_key');
            $newName = getExt($tmpName);
            $content = file_get_contents(get_path($fpath . $tmpName));
            $finalName = random(5) . ' ' . str_replace(['encrypted', 'descrypted', ' encrypted', ' descrypted'], '', $newName['filename']) . ' encrypted.' . $newName['extension'];  
            
            // hitung execution time
            $time_start = microtime(true);
            $this->Steganografi->enkripsi($public_key, $content, $finalName);

            $time_end = microtime(true);
            $time = $time_end - $time_start;


            unlink(get_path($fpath . $tmpName));
            response(['url' => base_url('public/docs/tmp/docs/' . $finalName), 'eta' => number_format($time, 2)]);
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()] + $th->getTrace(), 500);
        }
    }

    function deskripsi_post()
    {
        try {
            $this->load->helper('file_upload_helper');
            $this->load->model('Steganografi');
           
            $tmpName = customUploadFile($_FILES['file'], 'tmp');
            $private_key = $this->input->post('private_key');
            $fpath = get_path(DOCS_PATH . 'tmp/docs/');
            $newName = getExt($tmpName);
            $finalName = random(5) . ' ' . str_replace(['encrypted', 'descrypted', ' encrypted', ' descrypted'], '', $newName['filename']) . ' descrypted.' . $newName['extension'];

            // hitung execution time
            $time_start = microtime(true);
            $this->Steganografi->deskripsi($private_key, $tmpName, $finalName);

            $time_end = microtime(true);
            $time = $time_end - $time_start;

            unlink(get_path($fpath . $tmpName));
            response(['url' => base_url('public/docs/tmp/docs/' . $finalName), 'eta' => number_format($time, 2)]);
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }
    }

    function embed_post()
    {
        try {
            $this->load->model('Steganografi');
            
            // hitung execution time
            $time_start = microtime(true);
            $fname = $this->Steganografi->embed($_FILES);

            $time_end = microtime(true);
            $time = $time_end - $time_start;

            response(['url' => base_url('public/docs/tmp/video/' . $fname), 'eta' => number_format($time, 2)]);
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }
    }

    function ekstraksi_post()
    {
        $this->load->model('Steganografi');
        try {
            if ($_FILES['video']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['video']['tmp_name'])) {
                $videoContent = file_get_contents($_FILES['video']['tmp_name']);
            } else {
                response("Terjadi kesalahan, periksa kembali video yang di upload");
            }
            // hitung execution time
            $time_start = microtime(true);

            $fname = $this->Steganografi->ekstract($videoContent);

            $time_end = microtime(true);
            $time = $time_end - $time_start;

            response(['url' => base_url('public/docs/tmp/docs/' . $fname), 'eta' => number_format($time, 2)]);
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }
        response(['name' => $fname . '.txt']);
    }

    function rsaeof_post($act = null){
        if(empty($act))
            response("Ilegal akses", 403);
        $this->load->model('Steganografi');
        
        $time_start = microtime(true);

        if($act == 'enkrip'){
            $fname = $this->Steganografi->embed($_FILES, true, $this->input->post('public_key'));
           
            $time_end = microtime(true);
            $time = $time_end - $time_start;

            response(['url' => base_url('public/docs/tmp/video/' . $fname), 'eta' => number_format($time, 2)]);
        }elseif($act == 'deskrip'){
            if ($_FILES['video_enkripted']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['video_enkripted']['tmp_name'])) {
                $videoContent = file_get_contents($_FILES['video_enkripted']['tmp_name']);
            } else {
                response("Terjadi kesalahan, periksa kembali video yang di upload");
            }

            $fname = $this->Steganografi->ekstract($videoContent, true, $this->input->post('private_key'));
            $time_end = microtime(true);
            $time = $time_end - $time_start;

            response(['url' => base_url('public/docs/tmp/docs/' . $fname), 'eta' => number_format($time, 2)]);
        }

    }

    function file_get($name)
    {

        $name = urldecode($name);
        $tmp = explode(".", $name);
        $ext = end($tmp);

        if (!in_array($ext, ['mp4', 'mkv']))
            $fpath = get_path(DOCS_PATH . 'tmp/docs/' . $name);
        else
            $fpath = get_path(DOCS_PATH . 'tmp/video/' . $name);

        $ctype = getMimeType($ext);
        $this->load->helper('download');

        $data = file_get_contents($fpath);
        $data = explode('-separator-', $data);

        $name2 = urldecode($data[0]);
        $tmp2 = explode(".", $name2);
        $ext2 = end($tmp2);

        force_download($name2, $data[1]);
        exit;
        if($ext2 == 'pdf'){
            $this->load->library('Dompdf_gen');
            $this->dompdf_gen->display_pdf($data[1], $data[0]);
        }elseif(in_array($ext, ['mp4', 'mkv'])){
            $ctype = getMimeType($ext2);
            header("Pragma: public"); // required
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false); // required for certain browsers
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"".$name."\";" );
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($fpath));
            ob_clean();
            flush();
            readfile($fpath);   
        }
        else{
            if(empty($data[1])){
                $fpath2 = get_path(DOCS_PATH . 'tmp/docs/');
                file_put_contents($fpath2 . $data[0], $data[1]);

                $ctype = getMimeType($ext2);
                header("Pragma: public"); // required
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private",false); // required for certain browsers
                header("Content-Type: $ctype");
                header("Content-Disposition: attachment; filename=\"".$data[0]."\";" );
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . filesize($fpath2 . $data[0]));
                ob_clean();
                flush();
                readfile( $fpath2 . $data[0] );
                unlink($fpath2 . $data[0]);

            }else{
                force_download($data[0], $data[1]);
            }
            unlink($fpath);
        }
        
    }

    private function readfile($fpath, $ext){
        require_once APPPATH . 'libraries/FileReader.php';

        if($ext == 'pdf'){
            $this->load->helper('file_reader_helper');
            $c = readPdf($fpath)['text'];     
        }else{
            $fileReader = new FileReader($fpath);
            $c = $fileReader->convertToText();
        }

        return $c;
    }
}
