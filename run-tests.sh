#!/usr/bin/env bash

vendor/bin/phpunit --coverage-text --coverage-html coverage && \
vendor/bin/phpstan analyze -l 5 src -c phpstan.neon