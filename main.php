<?php
    require 'vendor/autoload.php';
    require 'config.php';

    use Aws\Api\DateTimeResult;

    $sdk = new Aws\Sdk($sharedConfig);

    $s3Client = $sdk->createS3();

    $result = $s3Client->listBuckets();

//    print_r($result);

    echo "BUCKETS:\n";
    /** @var DateTimeResult $dtr */
    foreach ($result->get('Buckets') as $bucket) {
        $dtr = $bucket['CreationDate'];
        $dtrString = $dtr->format(DATE_ATOM);
        echo "\t$bucket[Name] $dtrString\n";

        $result1 = $s3Client->listObjects([ 'Bucket' => $bucket['Name']]);
        foreach ($result1->get('Contents') as $item) {
            echo "\t\t$item[Key] $item[Size]\n";
        }
    }

    echo "\n";

    echo "TRANSFER_STATS:\n";
    $meta = $result->get('@metadata');
    $transferStats = $meta['transferStats'];
    foreach ($transferStats as $method => $stats) {
        $statCount = count($stats);
        echo "\t$method ($statCount):\n";
        foreach ($stats as $stat) {
            $statString = json_encode($stat);
            echo "\t\t$statString\n";
        }
    }