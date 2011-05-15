<?php

/**
 * Property class
 *
 * @package    Orient
 * @subpackage Query
 * @author     Alessandro Nadalin <alessandro.nadalin@gmail.com>
 */

namespace Orient\Query\Command;

use Orient\Query\Command;

class Property extends Command
{
  public function property($property)
  {
    $this->setToken('Property', array($property), false);
  }

  public function on($class)
  {
    $this->setToken('Class', array($class), false);
  }
}
