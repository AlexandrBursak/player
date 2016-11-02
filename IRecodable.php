<?php

interface IRecodable
{

  function record( $action );
  function rstop( $action );
  function rpause( $action );

}