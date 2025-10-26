<?php

namespace App\UrlEncoders;

use App\UrlEncoders\CodeGenerator;

class RandomBase62CodeGenerator implements CodeGenerator
{
    public function __construct(private int $length) { }

    public function encode(): string
    {
        return $this->randomBase62($this->length);
    }

    private function randomBase62(int $len): string {
        $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $bytes = random_bytes($len);
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $out .= $alphabet[ord($bytes[$i]) % 62];
        }
        return $out;
    }
}
