<?php

/**
 * @author williamylian
 *
 * Class UrlSafeRsa
 */

 namespace Xrsa;
class XRsa
{
    const CHAR_SET = "UTF-8";
    const BASE_64_FORMAT = "UrlSafeNoPadding";
    const RSA_ALGORITHM_KEY_TYPE = OPENSSL_KEYTYPE_RSA;
    const RSA_ALGORITHM_SIGN = OPENSSL_ALGO_SHA256;

    protected $public_key;
    protected $private_key;
    protected $key_len;

    public function __construct($pub_key, $pri_key = null)
    {
        $this->public_key = $pub_key;
        $this->private_key = $pri_key;

        try {
            $pub_id = openssl_get_publickey($this->public_key);
            if($pub_id === false)
                response("Public key Invalid", 403);
            $this->key_len = openssl_pkey_get_details($pub_id)['bits'];
        } catch (\Throwable $th) {
            response($th->getMessage(), 403);
        }
    }

    public static function privateDecryptCustom($encrypted, $pri_key){
        $pri_id = openssl_get_privatekey($pri_key);
        if($pri_id === false)
            response("Private key Invalid", 403);
            
        $key_len = openssl_pkey_get_details($pri_id)['bits'];
        $decrypted = "";
        $part_len = $key_len / 8;
        $base64_decoded = url_safe_base64_decode($encrypted);
        $parts = str_split($base64_decoded, $part_len);

        try {
            foreach ($parts as $part) {
                $decrypted_temp = '';
                openssl_private_decrypt($part, $decrypted_temp, $pri_key);
                $decrypted .= $decrypted_temp;
            }
        } catch (\Throwable $th) {
            response($th->getMessage(), 403);
        }

        return $decrypted;
    }

    public static function cekError(){
        $error = openssl_error_string();
        if($error !== false){
            response($error, 403);
        }
    }

    public static function createKeys($key_size = 2048)
    {
        $config = array(
            "private_key_bits" => $key_size,
            "private_key_type" => self::RSA_ALGORITHM_KEY_TYPE,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $private_key);
        $public_key_detail = openssl_pkey_get_details($res);
        $public_key = $public_key_detail["key"];

        return [
            "public_key" => $public_key,
            "private_key" => $private_key,
        ];
    }

    public function publicEncrypt($data)
    {
        $encrypted = '';
        $part_len = $this->key_len / 8 - 11;
        $parts = str_split($data, $part_len);

        try {
            foreach ($parts as $part) {
                $encrypted_temp = '';
                openssl_public_encrypt($part, $encrypted_temp, $this->public_key);
                $encrypted .= $encrypted_temp;
            }
        } catch (\Throwable $th) {
            response($th->getMessage(), 403);
        }

        return url_safe_base64_encode($encrypted);
    }

    public function privateDecrypt($encrypted)
    {
        $decrypted = "";
        $part_len = $this->key_len / 8;
        $base64_decoded = url_safe_base64_decode($encrypted);
        $parts = str_split($base64_decoded, $part_len);

        try {
            foreach ($parts as $part) {
                $decrypted_temp = '';
                openssl_private_decrypt($part, $decrypted_temp,$this->private_key);
                $decrypted .= $decrypted_temp;
            }
        } catch (\Throwable $th) {
            response($th->getMessage(), 403);
        }

        return $decrypted;
    }

    public function privateEncrypt($data)
    {
        $encrypted = '';
        $part_len = $this->key_len / 8 - 11;
        $parts = str_split($data, $part_len);

        try {
            foreach ($parts as $part) {
                $encrypted_temp = '';
                openssl_private_encrypt($part, $encrypted_temp, $this->private_key);
                $encrypted .= $encrypted_temp;
            }
        } catch (\Throwable $th) {
            response($th->getMessage(), 403);
        }

        return url_safe_base64_encode($encrypted);
    }

    public function publicDecrypt($encrypted)
    {
        $decrypted = "";
        $part_len = $this->key_len / 8;
        $base64_decoded = url_safe_base64_decode($encrypted);
        $parts = str_split($base64_decoded, $part_len);

        try {
            foreach ($parts as $part) {
                $decrypted_temp = '';
                openssl_public_decrypt($part, $decrypted_temp,$this->public_key);
                $decrypted .= $decrypted_temp;
            }
        } catch (\Throwable $th) {
            response($th->getMessage(), 403);
        }

        return $decrypted;
    }

    public function sign($data)
    {
        openssl_sign($data, $sign, $this->private_key, self::RSA_ALGORITHM_SIGN);

        return url_safe_base64_encode($sign);
    }

    public function verify($data, $sign)
    {
        $pub_id = openssl_get_publickey($this->public_key);
        $res = openssl_verify($data, url_safe_base64_decode($sign), $pub_id, self::RSA_ALGORITHM_SIGN);

        return $res;
    }
}