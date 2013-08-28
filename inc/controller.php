<?php
/**
 *
 */
include '../../conf/main.php';
include 'functions.php';
header('Content-Type: text/plain; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

class Controller
{
    function __construct()
    {
    }
    public function startSession()
    {
        global $sessionName;
        global $domain;
        session_name($sessionName);
        session_set_cookie_params(0, '/', "." . $domain);
        session_start();
    }
    public function getUrlParts()
    {
        return explode("/", str_replace("?" . $_SERVER['QUERY_STRING'], "", $_SERVER['REQUEST_URI']));
    }

    public function auth($user)
    {
        global $userHostName;
        if (!$_SESSION['auth'] || ($_SESSION['screen_name'] != $user)) {
            //$_SESSION['auth'] = null;
            //$_SESSION['screen_name'] = null;
            die("<script>window.location='{$userHostName}/user/login'</script>");
        }
    }

    public function authApiKey($user, $key){
        global $postgisdb;
        $postgisdb = $user;
        $settings_viewer = new Settings_viewer();
        $res = $settings_viewer->get();
        $apiKey = $res['data']['api_key'];
        if ($apiKey == $key && $key!=false) {
            return true;
        } else {
            return false;
        }
    }

    public function toJSON($response)
    {
        $callback = $_GET['jsonp_callback'];
        if ($callback) {
            return $callback . '(' . json_encode($response) . ');';
        } else {
            return json_encode($response);
        }
    }
}
