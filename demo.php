<?php

include "vendor/autoload.php";

$z = Ziptastic\Ziptastic\Lookup::create(getenv('ziptastic_api_key'));

print_R($z->lookup(48038));
