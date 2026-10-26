<?php

// Prepare writable /tmp directories for serverless execution on Vercel
$storageDirectories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
    '/tmp/storage/app/payment_proofs',
];

foreach ($storageDirectories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Forward the request to Laravel's standard front controller
require __DIR__ . '/../public/index.php';
