<?php

use EventSauce\EventSourcing\CodeGeneration\CodeDumper;
use EventSauce\EventSourcing\CodeGeneration\YamlDefinitionLoader;

include_once __DIR__.'/../vendor/autoload.php';

$files = array_map(
    function (SplFileInfo $fileInfo): string {
        return $fileInfo->getRealPath();
    },
    array_filter(
        iterator_to_array(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(__DIR__.'/_includes')
            )
        ),
        function (SplFileInfo $fileInfo) {
            if (strpos($fileInfo->getPath(), 'node_modules')) {
                return false;
            }

            return $fileInfo->isFile() && $fileInfo->getExtension() === 'yml';
        }
    )
);

foreach ($files as $path) {
    $contents = file_get_contents($path);
    $pathBase = pathinfo($path, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR.pathinfo($path, PATHINFO_FILENAME);
    file_put_contents($pathBase.'.md', "```yaml\n{$contents}\n```");
    $dumper = new CodeDumper();
    $loader = new YamlDefinitionLoader();
    $definitionGroup = $loader->load($path);
    $code = $dumper->dump($definitionGroup);
    file_put_contents($pathBase.'-output.md', "```php\n{$code}\n```");
}