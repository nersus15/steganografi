<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!method_exists($this,'fieldmapping')){
    function fieldmapping($config, $input, $defaultValue = array(), $petaNilai = array()){

        /** @var CI_Controller $ci */
        $ci =& get_instance();
        $isLoaded = $ci->load->config('forms');
        $configitem = $ci->config->item('form');
        $adaDefault = count($defaultValue) > 0;
        $adaPeta = count($petaNilai) > 0;
        $field = array();
        if(!$isLoaded || empty($configitem[$config]))
            response(['message' => 'Config form ' . $config . ' Tidak ditemukan'], 404);
        
        foreach($configitem[$config] as $k => $v){
            if($adaDefault && isset($defaultValue[$k])){
                if(isset($input[$k]) && (is_null($input[$k]) || $input[$k] == ''))
                    $field[$v] = $defaultValue[$k];
                else
                    $field[$v] = html_escape($input[$k]);
            }elseif((!$adaDefault || !isset($defaultValue[$k])) && isset($input[$k]))
                $field[$v] = html_escape($input[$k]);
        }
        
        
        if($adaPeta){
            foreach($petaNilai as $key => $f){
                foreach($f as $k => $v){
                    if($field[$key] == $k)
                        $field[$key] = $v;
                }
            }
        }
        foreach($field as $k => $f){
            if($f == '#unset') // nanti jika dia defaultnya unset dia dihapus dari arraya agar tidak update kolom itu
                unset($field[$k]);
        }
        return $field;
    }
}