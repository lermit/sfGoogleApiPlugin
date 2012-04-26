<?php

class GoogleApiMethod extends GoogleApiMethodTaskListBase
{
  public function configure()
  {
    parent::configure();

    $this->setMethod("GET");

    $this->appendToResourcePath("%%tasklist_id%%");

    $this->addRequiredResourceOptions("tasklist_id");
  }
}
