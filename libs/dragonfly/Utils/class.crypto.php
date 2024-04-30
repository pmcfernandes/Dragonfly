<?php
namespace Impedro\Dragonfly\Utils;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class Crypto128
{
    /**
     * Create a 128Bit encoded string
     *
     * @param $str
     * @param $salt
     * @return string
     */
    public static function encode($str, $salt)
    {
        return hash('sha512', crypt($str, md5($salt)));
    }

    /**
     * Gets Public key of certificate
     *
     * @param $certPath
     * @return mixed
     */
    public static function certPublicKey($certPath)
    {
        $fp = fopen($certPath, "r");
        $pub_key = fread($fp, 8192);
        fclose($fp);

        $pub = openssl_get_publickey($pub_key);
        $keyData = openssl_pkey_get_details($pub);
        return $keyData['key'];
    }
}
