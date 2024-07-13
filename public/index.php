<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// php内蔵サーバーでのシンボリックリンク機能（デプロイ時注意）

// if (preg_match('/^\/storage\/(.*)$/', $_SERVER["REQUEST_URI"], $matches)) {
//     $path = __DIR__.'/../storage/app/public/'.$matches[1];
//     if (file_exists($path)) {
//         return readfile($path);
//     } else {
//         http_response_code(404);
//         echo "File not found.";
//         return;
//     }
// }

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());



// existing code...

