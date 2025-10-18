<?php
// Create necessary directories
$basePath = dirname(__DIR__);
$dirs = [
    $basePath . '/public/assets/uploads',
    $basePath . '/public/assets/css', 
    $basePath . '/public/assets/js'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "Created directory: $dir<br>";
    } else {
        echo "Directory exists: $dir<br>";
    }
}

echo "Directory setup complete!";