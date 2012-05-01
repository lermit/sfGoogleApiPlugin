<?php

class GoogleApiMethodTaskBase extends GoogleApiMethodBase
{
  public function configure()
  {
    $this->setService(new GoogleApiServiceTask());
    $this->setScopes(array("tasks"));
  }
}
