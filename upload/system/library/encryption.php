<?php
final class Encryption {
    private $key;
    private $iv;

    public function __construct($key) {
        $this->key = hash('sha256', $key, true);
        $this->iv = openssl_random_pseudo_bytes(16);
    }

    public function encrypt($value) {
        $encrypted = openssl_encrypt($value, 'aes-256-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv);
        return strtr(base64_encode($this->iv . $encrypted), '+/=', '-_,');
    }

    public function decrypt($value) {
        $data = base64_decode(strtr($value, '-_,', '+/='));
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return trim(openssl_decrypt($encrypted, 'aes-256-cbc', $this->key, OPENSSL_RAW_DATA, $iv));
    }
}
