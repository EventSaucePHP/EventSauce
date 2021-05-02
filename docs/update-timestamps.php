<?php

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

function creation_date(string $path): string
{
    $date = shell_exec("git --no-pager log --diff-filter=A -1 --format=\"%ai\" -- " . $path);

    return explode(' ', $date)[0];
}

function modified_date(string $path): ?string
{
    $date = shell_exec("git --no-pager log -1 --format=\"%ai\" -- " . $path);

    return explode(' ', $date ?: '')[0] ?? null;
}

foreach ($files as $path) {
    $contents = file_get_contents($path);
    $result = preg_match('/^---\n((?:.*\n)*)---/mU', $contents, $matches, PREG_OFFSET_CAPTURE);

    if ($result === false || ! isset($matches[1])) {
        continue;
    }

    [$info, $offset] = $matches[1];
    $end = $offset + mb_strlen($info);
    $updatedInfo = trim($info);
    $document = rtrim(substr($contents, $end)) . "\n";

    $updates = [
        'published_at' => creation_date($path) ?: date('Y-m-d'),
        'updated_at' => modified_date($path) ?: date('Y-m-d'),
    ];

    foreach ($updates as $label => $date) {
        $updatedInfo = preg_replace("/{$label}: .*/", "{$label}: {$date}", $updatedInfo);

        if (strpos($updatedInfo, $label) === false) {
            $updatedInfo = rtrim($updatedInfo) . "\n{$label}: {$date}";
        }
    }

    $updatedContents = "---\n{$updatedInfo}\n{$document}";

    if ($contents !== $updatedContents) {
        file_put_contents($path, $updatedContents);
    }
}
