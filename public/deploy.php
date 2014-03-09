<?php #!/usr/bin/env /usr/bin/php

error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(0);
$date = date('Y_m_d', time());
$output = shell_exec("/home/mercatino/deploy.sh");
//echo $output; just for test
file_put_contents("/home/mercatino/logs/deploy/deploy_$date.txt", $output, FILE_APPEND);

