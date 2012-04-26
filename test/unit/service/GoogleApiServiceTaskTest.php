<?php

require_once dirname(__FILE__).'/../bootstrap/unit.php';

$t = new lime_test(1);

$service = new GoogleApiServiceTask();

$t->is($service->getApiName(), 'test');
$t->is($service->getApiVersion(), 'v1');
