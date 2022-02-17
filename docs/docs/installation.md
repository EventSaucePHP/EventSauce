---
permalink: /docs/installation/
title: Installation
---

EvenSauce consists of multiple parts. Besides the main package you'll need _persistence_ and a _dispatcher_.

First you'll need to install the main package:

```bash
composer require eventsauce/eventsauce
```

There is test tooling available, install it using:

```bash
composer require --dev eventsauce/test-utilities
```

To use code generation, install it by running:

```bash
composer require --dev eventsauce/code-generation
```
