<?php

require_once dirname(__FILE__).'/../../../bootstrap/unit.php';
 
$t = new lime_test(2);

$serviceBase = new GoogleApiServiceBase();

// Test __construct
$t->is($serviceBase->getApiEndPoint(), "https://www.googleapis.com", "Correct default api endpoint");

// Test getUrl
$t->diag('->getUrl');
$serviceBase->setApiVersion("v1");
$serviceBase->setApiName("exemple_name");
$t->is($serviceBase->getUrl(), "https://www.googleapis.com/exemple_name/v1");
