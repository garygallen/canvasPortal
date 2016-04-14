<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


require('httpful.phar');

$user_mail = $_SERVER['MELLON_mail'];

$rest_base = 'https://wakeidm-test.wcpss.net:8443/openidm/';
$rest_path = 'managed/user?_queryId=for-userName&uid='.$user_mail;
$rest_id = 'wcpss-admin-tool';
$rest_pw = 'okG788syc!496';

$response = \Httpful\Request::get($rest_base . $rest_path)
    ->addHeader('X-OpenIDM-Username', $rest_id)
    ->addHeader('X-OpenIDM-Password', $rest_pw)
    ->sendsAndExpects('json')
    ->send();

//echo '<pre>'; var_export($response); echo '</pre>';

$user_obj = $response->body->result[0];
//echo '<pre>'; var_export($user_obj); echo '</pre>';

//list($mUser, $trash) = explode('@', $user_mail);
$mUser = $user_mail;  //member user
//echo 'mUser: '.$mUser.'<br />';

/*
function get_json($rest_path, $cache_time = 3600)
{
    // STANDARD USAGE: get_json(openidm uri)->body;
    global $cache_path, $rest_base, $rest_id, $rest_pw;
    $cache = new wakeID_Cache($cache_path);
    $response = $cache->get($rest_path, $cache_time);
    if ( $response === false ) {
        /** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUndefinedNamespaceInspection *
$response = \Httpful\Request::get($rest_base . $rest_path)
->addHeader('X-OpenIDM-Username', $rest_id)
->addHeader('X-OpenIDM-Password', $rest_pw)
->sendsAndExpects('json')
->send();
if ( $response->code == 200 ) {
$cache->set($rest_path, $response);
}
}
return $response;
}

 */
?>