<?php

include "vendor/autoload.php";

$z = new Ziptastic\Ziptastic(getenv('ziptastic_api_key'));

print_R($z->lookup(48038));