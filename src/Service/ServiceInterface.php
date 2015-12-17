<?php namespace Ziptastic\Ziptastic\Service;

interface ServiceInterface
{
  public function get($url, $headers);
}