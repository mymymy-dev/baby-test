<?php

/**
 * Ignorované súbory je možné meniť v súbore `./rsync-ignore`
 *
 * spustenie
 * php rsync-deploy-production.php
 */

$host = 'shell.r6.websupport.sk';
$user = 'uid4285016';
$port = 28526;
$path = '~/t-rextravel.com/sub/contractions';

$remote = $user . '@' . $host . ':' . $path;

//exec('rsync -avz --progress --exclude-from \'rsync-ignore\' -e "ssh -p ' . $port . '" . trex@87.197.105.187:t-rextravel.com');
exec('rsync -rhvzP --no-perms --no-owner --no-group --stats --exclude-from \'rsync-ignore\' /Users/sucho/WAME/Laravel/kontackie/ -e "ssh -p ' . $port . '" ' . $remote);
