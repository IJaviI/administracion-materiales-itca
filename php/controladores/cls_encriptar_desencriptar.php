<?php
class cls_encriptar_desencriptar {
    public function encriptar_desencriptar($accion, $texto){
        $output = false;
        $encriptarmetodo = "AES-256-CBC";
        $palabrasecreta = "app para prestamos itca 2799823";
        $iv = "C9JSHDKASHDK/AHS7JS";
        $key = hash("sha256", $palabrasecreta);
        $siv = substr(hash("sha256",$iv),0,16);
        if($accion=="encriptar"){
            $salida = openssl_encrypt($texto,$encriptarmetodo,$key,0,$siv);
        } else if($accion=="desencriptar"){
            $salida = openssl_decrypt($texto,$encriptarmetodo,$key,0,$siv);
        }
        return $salida;
    }
}
?>