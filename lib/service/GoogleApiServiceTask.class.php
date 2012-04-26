<?php

class GoogleApiServiceTask extends GoogleApiServiceBase
{
  public function configure()
  {
    $this
      ->setApiName('tasks')
      ->setApiVersion('v1');
  }
}
