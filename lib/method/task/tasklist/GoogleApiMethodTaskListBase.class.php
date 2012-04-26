<?php

class GoogleApiMethodTaskListBase extends GoogleApiMethodTaskBase
{
  public function configure()
  {
    parent::configure();

    $this->setResourcePath("/users/@me/lists");
  }
}
