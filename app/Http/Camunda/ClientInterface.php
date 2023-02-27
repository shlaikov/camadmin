<?php

namespace App\Http\Camunda;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public static function index(): ResponseInterface;

    public static function find(): ResponseInterface;

    public static function create(): ResponseInterface;

    public static function delete(): ResponseInterface;

    public static function update(): ResponseInterface;
}
