<?php namespace Ziptastic\Service;

interface ServiceInterface
{
  public function get($url, $headers);
}