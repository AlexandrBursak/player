<?php

interface IPlayable
{

  function start( $action );
  function stop( $action );
  function pause( $action );

}