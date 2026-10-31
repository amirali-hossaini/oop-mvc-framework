<?php

use App\Core\Router;

require_once dirname(__DIR__) . '/app/bootstrap.php';

(new Router)->dispatch();