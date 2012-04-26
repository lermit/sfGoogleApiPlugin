<?php

class GoogleApiMethodTaskListList extends GoogleApiMethodTaskListBase
{
  public function configure()
  {
    parent::configure();

    $this->setMethod("GET");
  }
}
