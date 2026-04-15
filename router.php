<?php
$requested_file = $_SERVER['REQUEST_URI'];

if ($requested_file === '/' || $requested_file === '') {
    include 'page-accueil.php';
    return true;
}

if (file_exists(__DIR__ . $requested_file) && is_file(__DIR__ . $requested_file)) {
    return false; // Let PHP serve the file
}

// For all other requests, route to the file
$file = __DIR__ . $requested_file;
if (file_exists($file) && is_file($file)) {
    include $file;
    return true;
}

http_response_code(404);
echo "404 Not Found";
return true;
?>
