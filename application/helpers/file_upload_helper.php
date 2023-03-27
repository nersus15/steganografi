<?php
defined('BASEPATH') or exit('No direct script access allowed');

function uploadImage($file, $iname, $type = 'profile')
{
    /** @var CI_Controller $ci */
    $ci = &get_instance();
    $file = $file[$iname];
    $file = explode('.', $file['name']);
    $file_name = random(8) . '.' . $file[count($file) - 1];
    $isLoaded = $ci->load->config('upload_path');
    $pathConfig = $ci->config->item('image');

    if ($isLoaded && isset($pathConfig[$type]))
        $config['upload_path'] = get_path($pathConfig[$type]);
    else
        $config['upload_path'] = ASSETS_PATH;

    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['max_size'] = '';
    $config['file_name'] = $file_name;


    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);

    if (!$ci->upload->do_upload($iname)) {
        response(['message' => print_r($ci->upload->display_errors(), true), 'type' => 'error'], 500);
    }

    return $file_name;
}

function uploadFile($file, $iname, $type = 'tmp')
{
    /** @var CI_Controller $ci */
    $ci = &get_instance();
    $file = $file[$iname];
    $file = explode('.', $file['name']);
    $file_name = random(8) . '.' . $file[count($file) - 1];
    $isLoaded = $ci->load->config('upload_path');
    $pathConfig = $ci->config->item('file');

    if ($isLoaded && isset($pathConfig[$type]))
        $config['upload_path'] = get_path($pathConfig[$type]);
    else
        $config['upload_path'] = ASSETS_PATH;

    $config['allowed_types'] = 'pdf|doc|docx|ppt|txt';
    $config['max_size'] = '';
    $config['file_name'] = $file_name;


    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);

    if (!$ci->upload->do_upload($iname)) {
        response(['message' => print_r($ci->upload->display_errors(), true), 'type' => 'error'], 500);
    }

    return $file_name;
}

function uploadVideo($file, $iname, $type = 'profile')
{
    /** @var CI_Controller $ci */
    $ci = &get_instance();
    $file = $file[$iname];
    $file = explode('.', $file['name']);
    $file_name = random(8) . '.' . $file[count($file) - 1];
    $isLoaded = $ci->load->config('upload_path');
    $pathConfig = $ci->config->item('video');

    if ($isLoaded && isset($pathConfig[$type]))
        $config['upload_path'] = get_path($pathConfig[$type]);
    else
        $config['upload_path'] = ASSETS_PATH;

    $config['allowed_types'] = 'mp4|mkv';
    $config['max_size'] = '';
    $config['file_name'] = $file_name;


    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);

    if (!$ci->upload->do_upload($iname)) {
        response(['message' => print_r($ci->upload->display_errors(), true), 'type' => 'error'], 500);
    }

    return $file_name;
}

function delete_img($nama, $type = 'profile')
{
    $ci = &get_instance();
    $isLoaded = $ci->load->config('upload_path');
    $pathConfig = $ci->config->item('image');
    if ($isLoaded && isset($pathConfig[$type]))
        $upload_path = get_path($pathConfig[$type]);
    else
        $upload_path = ASSETS_PATH;

    if (file_exists($upload_path . $nama))
        unlink($upload_path . $nama);
}


function customUploadFile($file, $type = 'tmp', $conf = null)
{
    $config = array(
        'default' => null,
        'asal' => null,
        'maks' => 6000000 * 3,
        'isEnkripsi' => true,
    );
    if (!empty($conf))
        foreach ($conf as $k => $v)
            $config[$k] = $v;

    /** @var CI_Controller $ci */
    $ci = &get_instance();
    $isLoaded = $ci->load->config('upload_path');
    $pathConfig = $ci->config->item('file');


    $nama_file = $file['name'];
    $ukuran_file = $file['size'];
    $error = $file['error'];
    $tmp = $file['tmp_name'];
    $format_sesuai = ['doc', 'docx', 'ppt', 'pptx', 'pdf', 'txt'];
    $format_file = explode('.', $nama_file);
    $format_file = strtolower(end($format_file));
    if (!in_array($format_file, $format_sesuai)) {
        response(['message' => 'Gagal, Pilih file yang Valid'], 500);
    } elseif ($ukuran_file > $config['maks']) {
        response(['message' => 'Gagal, size file yang dipilih terlalu besar'], 500);
    } else {
        if ($config['isEnkripsi']) {
            $namaFinal = random(10);
            $namaFinal .= ".";
            $namaFinal .= $format_file;
        } else
            $namaFinal =  $config['name'] . '.' . $format_file;

        try {
            move_uploaded_file($tmp, get_path($pathConfig[$type] . '/' . $namaFinal));
        } catch (\Exception $err) {
            response(['message' => 'Gagal upload file', 'err' => $err->getMessage()], 500);
        }
       
        return $namaFinal;
    }
}
