<?php

namespace App\UrlEncoders;

interface CodeGenerator
{
    public function encode(): string;
}
