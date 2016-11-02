<?php

class Player implements IPlayable, IRecodable
{
  private $file = 'data_storeg.log';
  private $content;

  const TIME_START = 'time-start';
  const TIME_PAUSE_START = 'time-pause';
  const WHILE_TIME_PAUSE = 'while-pause';

  function start( $action )
  {
    $this->set_content( $action );
  }

  function stop( $action )
  {
    $this->set_content( $action );
  }

  function pause( $action )
  {
    $this->set_content( $action );
  }

  function record( $action )
  {
    $this->set_content( $action );
    if ( $unix_time_start = $this->get_session( self::TIME_START ) )
    {
      $this->rstop( '' );
    }
    else
    {
      $this->set_session( self::TIME_START, time() );
    }
  }

  function rstop( $action )
  {
    $this->set_content( $action );
    if ( $unix_time_start = $this->get_session( self::TIME_START ) )
    {
      $time_pause = 0;
      if ( $prev_pause = $this->get_session( self::WHILE_TIME_PAUSE ) )
      {
        $time_pause = $prev_pause;
      }
      $unix_time_now = time();
      if ( $unix_time_pause_start = $this->get_session( 'TIME_PAUSE_START' ) )
      {
        $unix_time_now = $unix_time_pause_start;
      }
      $time_record = $unix_time_now - $unix_time_start - $time_pause;
      $this->set_content( 'record time: ' . $time_record . ' seconds ');
      $this->clear_session();
    }
    else
    {
      $this->set_content( 'player does not record' );
      return 'player does not record';
    }
  }

  function rpause( $action )
  {
    $this->set_content( $action );
    if ( $unix_time_start = $this->get_session( self::TIME_START ) )
    {
      if ( $unix_time_pause = $this->get_session( self::TIME_PAUSE_START ) )
      {
        $unix_time_now = time();
        $prev_time_pause = 0;
        if ( $prev_pause = $this->get_session( self::WHILE_TIME_PAUSE ) )
        {
          $prev_time_pause = $prev_pause;
        }
        $full_time = $unix_time_now - $unix_time_pause - $prev_time_pause;
        $this->set_session( self::TIME_PAUSE_START, '' );
        $this->set_session( self::WHILE_TIME_PAUSE, $full_time );
        $this->set_content( 'pause time: ' . $full_time . ' seconds ' );
      }
      else
      {
        $this->set_session( self::TIME_PAUSE_START, time() );
        $this->set_content( 'pause start' );
      }
    }
    else
    {
      $this->set_content( 'player does not record' );
      return 'player does not record';
    }
  }

  function save_data()
  {
    if ( !empty( $this->content ) )
    {
      @file_put_contents( $this->file, $this->content . PHP_EOL, FILE_APPEND );
    }
  }

  function get_content()
  {
    return $this->content;
  }

  function set_content( $attr = null )
  {
    if ( $attr )
    {
      if ( empty( $this->get_content() ) )
      {
        $this->content = time() . ': ' . $attr;
      }
      else
      {
        $this->append_content( $attr );
      }
    }
  }

  function append_content( $attr = null )
  {
    if ( $attr )
    {
      $this->content = $this->get_content() . ' - ' . $attr;
    }
  }

  function get_session( $param )
  {
    if ( !empty( $_SESSION[$param] ) )
    {
      return $_SESSION[ $param ];
    }
    else
    {
      return false;
    }
  }

  function set_session( $param, $attr )
  {
    $_SESSION[$param] = $attr;
  }

  function clear_session()
  {
    $_SESSION = [];
    unset( $_SESSION );
  }

}