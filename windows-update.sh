#!/bin/bash

composer install

taskkill -F -IM php-cgi.exe -T
