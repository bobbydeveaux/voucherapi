#!/bin/bash
./vendor/bin/phpunit -c app/phpunit.xml 
./vendor/bin/phpcs --config-set default_standard PSR2
./vendor/bin/phpcs -n src
./vendor/bin/sami.php update config/sami.php
