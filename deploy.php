<?php

/**
 * Ignorované súbory je možné meniť v súbore `./rsync-ignore`
 *
 * spustenie
 * php rsync-deploy-production.php
 */

$host = 'shell.r6.websupport.sk';
$user = 'uid4285016';
$port = 28350;
$path = '~/t-rextravel.com/sub/baby';

$remote = $user . '@' . $host . ':' . $path;

exec('rsync -rhvzP --no-perms --no-owner --no-group --stats --exclude-from \'rsync-ignore\' /Users/sucho/Projects/Laravel/baby/ -e "ssh -p ' . $port . '" ' . $remote);
