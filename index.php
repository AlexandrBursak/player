<?php
session_start();

/**
 * Class autoloader
 */
spl_autoload_register(function ($class)
{
  $file_name = str_replace('\\', '/', $class). '.php';

  if(file_exists($file_name))
  {
    require_once($file_name);
  }
});

if ( empty($_POST) ) {

  $message = json_encode([
    'debug' => $_POST,
    'status' => 'error',
    'message' => 'Data wrong\'s'
  ]);

}

$access_action = [ 'start', 'stop', 'pause', 'record', 'rstop', 'rpause' ];

if ( $_POST['action'] && in_array( strtolower($_POST['action']), $access_action ) ) {

  $action = strtolower($_POST['action']);

  $player = new Player();
  $return = $player->$action( $action );
  if ( !empty($return) )
  {
    $message = json_encode( [
      'status' => 'error',
      'message' => $return
    ] );
  }
  else
  {
    $message = json_encode( [
      'debug' => $_POST,
      'status' => 'success',
      'message' => 'Record ' . $action . 's'
    ] );
  }

  $player->save_data();

}
else
{
  $message = json_encode([
    'debug' => $_POST,
    'status' => 'error',
    'message' => 'Data wrong\'s'
  ]);
}

echo $message;
