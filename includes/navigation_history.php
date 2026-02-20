<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['nav_history'])) {
    $_SESSION['nav_history'] = [];
}

$current_page = $_SERVER['PHP_SELF'];
$query_string = $_SERVER['QUERY_STRING'];

parse_str($query_string, $params);
unset($params['go_back']);
$clean_query = http_build_query($params);

$current_url = $current_page . ($clean_query ? '?' . $clean_query : '');

if (isset($_GET['go_back']) && $_GET['go_back'] == '1') {

    if (count($_SESSION['nav_history']) > 0) {
        array_pop($_SESSION['nav_history']);
    }
    

    if (count($_SESSION['nav_history']) > 0) {
        $previous_page = array_pop($_SESSION['nav_history']);
        header("Location: " . $previous_page);
        exit();
    } else {

        header("Location: index.php");
        exit();
    }
}


$last_page = end($_SESSION['nav_history']);
if ($last_page !== $current_url) {
    $_SESSION['nav_history'][] = $current_url;
}


if (count($_SESSION['nav_history']) > 20) {
    array_shift($_SESSION['nav_history']);
}

function get_back_url() {
    if (isset($_SESSION['nav_history']) && count($_SESSION['nav_history']) > 1) {

        $history = $_SESSION['nav_history'];
        $current_page = $_SERVER['PHP_SELF'];
        

        return $current_page . (strpos($current_page, '?') !== false ? '&' : '?') . 'go_back=1';
    }
    return null;
}


function has_history() {
    return isset($_SESSION['nav_history']) && count($_SESSION['nav_history']) > 1;
}
?>