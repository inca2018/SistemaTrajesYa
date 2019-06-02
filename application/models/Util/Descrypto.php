
<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Descrypto extends CI_Model {

 public function Desencriptar($texto){
        $cipher = "rijndael-128"; 
        $mode = "cbc"; 
        $secret_key = "D4:6E:AC:3F:F0:BE"; 
        //iv length should be 16 bytes 
        $iv = "fedcba9876543210"; 

        // Make sure the key length should be 16 bytes 
        $key_len = strlen($secret_key); 
        if($key_len < 16 ){ 
        $addS = 16 - $key_len; 
        for($i =0 ;$i < $addS; $i++){ 
        $secret_key.=" "; 
        } 
        }else{ 
        $secret_key = substr($secret_key, 0, 16); 
        } 
        $td = mcrypt_module_open($cipher, "", $mode, $iv); 
        mcrypt_generic_init($td, $secret_key, $iv); 
        $decrypted_text = mdecrypt_generic($td, $this->hex2bin($texto)); 
        mcrypt_generic_deinit($td); 
        mcrypt_module_close($td); 
        //echo trim($decrypted_text); 
        return trim($decrypted_text);
    }
    
    public function hex2bin($data){
        $bin = "";
        $i = 0;
        do {
        $bin .= chr(hexdec($data{$i}.$data{($i + 1)}));
        $i += 2;
        } while ($i < strlen($data));

        return $bin;
        }
}




?>
 