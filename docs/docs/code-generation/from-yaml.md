---
permalink: /docs/code-generation/from-yaml/
title: Code Generation From YAML
published_at: 2019-11-09
updated_at: 2019-11-09
---

An easy way to define your commands and events, is by using the YAML definition loader. This
loader creates a `DefinitionGroup` for you and provides an easy format to declare your
classes in. The benefit of using YAML is that it gives a small interface that is easy
to read and gives you a great overview of what you've got defined.

```php
<?php

use EventSauce\EventSourcing\CodeGeneration\CodeDumper;
use EventSauce\EventSourcing\CodeGeneration\YamlDefinitionLoader;

$loader = new YamlDefinitionLoader();
$dumper = new CodeDumper();
$phpCode = $dumper->dump($loader->load('path/to/definition.yml'));
file_put_contents($destination, $phpCode);
```

Here's an example YAML file containing some command and event definitions.

{% include example-definition.md %}

Which compiles to the following PHP file:
 
{% include example-definition-output.md %}
