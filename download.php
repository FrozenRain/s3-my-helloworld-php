<?php
    require 'vendor/autoload.php';
    require 'config.php';

    if (count($argv) < 2) {
        die("You must specify KEY and (optionally) FILENAME, RANGE");
    }

    $key = $argv[1];
    $filename = @$argv[2] ?? $key;
    $range = @$argv[3] ?? '0';

    $sdk = new Aws\Sdk($sharedConfig);

    $s3Client = $sdk->createS3();

    $result = $s3Client->listBuckets();
    $buckets = $result->get('Buckets');
    if (empty($buckets)) {
        die("No any Buckets");
    }
    $bucket = $buckets[0]['Name'];

    $result = $s3Client->getObject([
        'Bucket' => $bucket,
        'Key' => $key,
        'Range' => "bytes=$range"
    ]);

    file_put_contents($filename, $result->get('Body'));