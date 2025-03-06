#!/usr/bin/env bash

vendor/bin/phpunit --coverage-text --coverage-html coverage && \
vendor/bin/phpstan analyze src -c phpstan.neon