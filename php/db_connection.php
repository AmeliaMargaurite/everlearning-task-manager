<?php 

function OpenConn() {
    $config = parse_ini_file(CONFIG_PATH);

    try {
        if (IS_LIVE) {
            $conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pw'], $config['db_name']);
        } else {
            $conn = new mysqli($config['local_db_host'], $config['local_db_user'], 
            $config['local_db_pw'], $config['local_db_name']);
        }
        if ($conn === false) {
            echo "Connect failed: %s\n" . $conn -> error;
        } 
        else return $conn;
    } catch(Exception $ex) {
        redirect_to(HOME_URL . 'db-failed');
    }
}

function CloseConn($conn) {
    $conn->close();
}

?>  