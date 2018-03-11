<?php

use SebastianBergmann\CodeCoverage\Report\PHP;

$files = array_map(
    function (SplFileInfo $fileInfo): string {
        return $fileInfo->getRealPath();
    },
    array_filter(
        iterator_to_array(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(__DIR__)
            )
        ),
        function (SplFileInfo $fileInfo) {
            if (strpos($fileInfo->getPath(), 'node_modules')) {
                return false;
            }

            return $fileInfo->isFile() && $fileInfo->getExtension() === 'md';
        }
    )
);

foreach ($files as $path) {
    $contents = file_get_contents($path);
    $result = preg_match('/^---\n((?:.*\n)*)---/mU', $contents, $matches, PREG_OFFSET_CAPTURE);

    if ($result === false || ! isset($matches[1])) {
        continue;
    }

    list($info, $offset) = $matches[1];
    $end = $offset + mb_strlen($info);
    $document = rtrim(substr($contents, $end)) . "\n";

    $updates = [
        'published_at' => date('Y-m-d', filectime($path)),
        'updated_at'   => date('Y-m-d', filemtime($path)),
    ];

    $updatedInfo = trim($info);

    foreach ($updates as $label => $date) {
        if ($label === 'published_at' && strpos($updatedInfo, $label) !== false) {
            continue;
        }

        $updatedInfo = preg_replace("/(^{$label}: .*)/", "{$label}: {$date}", $updatedInfo);

        if (strpos($updatedInfo, $label) === false) {
            $updatedInfo = rtrim($updatedInfo) . "\n{$label}: {$date}";
        }
    }

    $updatedContents = "---\n{$updatedInfo}\n{$document}";
    file_put_contents($path, $updatedContents);
}