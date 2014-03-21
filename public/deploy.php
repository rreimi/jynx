<?php #!/usr/bin/env /usr/bin/php

error_reporting(E_ALL);

//validar si el request viene de github

$requestLog = $_SERVER['HTTP_REFERER'];
file_put_contents('/home/mercatino/logs/deploy/request.txt', $_SERVER['HTTP_REFERER'], FILE_APPEND);

if (isset($_REQUEST['payload'])) {

    $requestLog = "PAYLOAD DATA: " . $_REQUEST['payload'];
    file_put_contents('/home/mercatino/logs/deploy/request.txt', $requestLog, FILE_APPEND);

    try {
        $payload = json_decode($_REQUEST['payload']);
    }
    catch(Exception $e) {
        //log the error
        file_put_contents('/home/mercatino/logs/deploy/error.txt', $e . ' ' . $payload, FILE_APPEND);
        exit(0);
    }

    if ($payload->ref === 'refs/heads/master') {
        ini_set('display_errors', '1');
        set_time_limit(0);
        $date = date('Y_m_d', time());
        $output = shell_exec("/home/mercatino/deploy.sh");
        //echo $output; just for test
        file_put_contents("/home/mercatino/logs/deploy/deploy_$date.txt", $output, FILE_APPEND);

    } else {
        file_put_contents("/home/mercatino/logs/deploy/push.txt", $_REQUEST['payload'], FILE_APPEND);
    }
}
