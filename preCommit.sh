#!/bin/bash
./vendor/bin/phpunit -c app/phpunit.xml 
./vendor/bin/phpcs -n src
./vendor/bin/sami.php update config/sami.php
