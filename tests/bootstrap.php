<?php declare(strict_types=1);

$composerVendorDirectory = __DIR__ . '/../vendor';

require $composerVendorDirectory . '/autoload.php';

$hamcrestRelativePath = 'hamcrest/hamcrest-php/hamcrest/Hamcrest.php';

if (DIRECTORY_SEPARATOR !== '/') {
    $hamcrestRelativePath = str_replace('/', DIRECTORY_SEPARATOR, $hamcrestRelativePath);
}
$hamcrestPath = $composerVendorDirectory . DIRECTORY_SEPARATOR . $hamcrestRelativePath;

require_once $hamcrestPath;
