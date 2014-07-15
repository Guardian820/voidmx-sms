<?php

/*
 * (c) 2013 Yann Guineau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Security
{
    /**
    * Génere un token CSRF
    **/
    public function generateToken()
    {
        /*$token = uniqid();*/

        $charset = "0123456789abcdefghijklmnopqrstuvwxyz";
        $token = "";

        for($i = 0; $i < 5; $i++)
            $token .= $charset[rand(0, strlen($charset)-1)];

        $_SESSION['token'] = $token;
        $_SESSION['token_time'] = time();
    }

    /**
    * Vérifie le token CSRF.
    **/
    public function AntiCSRF()
    {
        if(isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($_GET['tk']))
        {
            if($_SESSION['token'] == $_GET['tk'])
            {
                $timestamp_ancien = time() - (15 * 60);
                if($_SESSION['token_time'] >= $timestamp_ancien)
                {
                    $_SESSION['token_time'] = time();
                    return true;
                }
            }
        }

        $this->generateToken();
        return false;
    }

    /**
    * Fonction anti failles XSS
    * @param $string Text à traiter
    **/
    public static function AntiXSS($string)
    {
        $string = preg_replace('!<script>(.+)</script>!isU', '<removed>$1</removed>', $string);
        $string = preg_replace('!<script (.+)>(.+)</script>!isU', '<removed $1>$2</removed>', $string);
        //$string = htmlentities($string);

        return $string;
    }

    /**
    * Encryption des données en RC4.
    * @param $data Données à encrypter
    **/
    static function rc4Encrypt($data)
    {
        //return $this->ascii2hex($this->rc4($this->rc4key, $data));
        return self::ascii2hex(self::rc4(Conf::$security['rc4'], $data));
    }
    
    /**
    * Dencryption des données en RC4.
    * @param $data Donnée à decrypter
    **/
    static function rc4Decrypt($data)
    {
        //return $this->rc4($this->rc4key, $this->hex2ascii($data));
        return self::rc4(Conf::$security['rc4'], self::hex2ascii($data));
    }
    
    /**
    * Fonction RC4.
    * @param $key Clé d'encryption
    * @param $str Données a encrypter/decrypter
    **/
    static function rc4($key, $str)
    {
        $s = array();
        for ($i = 0; $i < 256; $i++)
        {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++)
        {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($str); $y++)
        {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $res;
    }

    /**
    * Convertion ASCII vers HEX.
    * @param $ascii Données en ASCII
    **/
    static function ascii2hex($ascii)
    {
        $hexadecimal = '';
        for ($i = 0; $i < strlen($ascii); $i++)
        {
            $byte = strtoupper(dechex(ord($ascii{$i})));
            $byte = str_repeat('0', 2 - strlen($byte)).$byte;
            //$hexadecimal.=$byte." ";
            $hexadecimal.=$byte; // sans espaces
        }
        return $hexadecimal;
    }

    /**
    * Convertion HEX vers ASCII.
    * @param $hex... Données en HEX
    **/
    static function hex2ascii($hexadecimal)
    {
        $ascii = '';
        $hexadecimal=str_replace(" ", "", $hexadecimal);
        for($i=0; $i<strlen($hexadecimal); $i=$i+2)
        {
            $ascii.=chr(hexdec(substr($hexadecimal, $i, 2)));
        }
        return $ascii;
    }
}