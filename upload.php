<?php
    require 'vendor/autoload.php';
    require 'config.php';

    if (count($argv) < 3) {
        die("You must specify KEY and SOURCE");
    }

    $key = $argv[1];
    $source = $argv[2];

    $sdk = new Aws\Sdk($sharedConfig);

    $s3Client = $sdk->createS3();

    $result = $s3Client->listBuckets();
    $buckets = $result->get('Buckets');
    if (empty($buckets)) {
        die("No any Buckets");
    }
    $bucket = $buckets[0]['Name'];
    $content = file_get_contents($source);

    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key' => $key,
        'Body' => $content
    ]);

    echo $result['ObjectURL'] . PHP_EOL;