<?php

require_once dirname(__FILE__).'/../../../bootstrap/unit.php';

$t = new lime_test(1);

$methodBase = new GoogleApiMethodBase();

// Generate a generic service
$service = new GoogleApiServiceTask();

// Test setService
$t->diag("->setService");

try {
  $methodBase->setService(null);
  $t->fail('null service throw an exception');
} catch( Exception $e ) {
  $t->pass('null service throw an exception');
}

try {
  $methodBase->setService(new DateTime());
  $t->fail('non GoogleApiServiceBase service throw an exception');
} catch( Exception $e) {
  $t->pass('non GoogleApiServiceBase service throw an exception');
}

try {
  $methodBase->setService($service);
  $t->pass('GoogleApiServiceBase will work');
} catch ( Exception $e ) {
  $t->fail('GoogleApiServiceBase will work');
}

// Test getService
$t->diag('->getService');
$methodBase->setService($service);
$t->is($methodBase->getService(), $service, "Stored service is correctly retreive");

// Test setResourcePath
$t->diag('->setResourcePath()');
try {
  $methodBase->setResourcePath("azerty");
  $t->pass("Accept parameter");
} catch ( Exception $e ) {
  $t->fail("Accept parameter");
}

// Test getResourcePath
$t->diag('->getResourcePath');
$methodBase = new GoogleApiMethodBase();
$t->is($methodBase->getResourcePath(), null, "Default resource path is null");
$methodBase->setResourcePath("my_service_is_funny");
$t->is($methodBase->getResourcePath(), "my_service_is_funny");
$methodBase->setResourcePath("myéè");
$t->is($methodBase->getResourcePath(), "myéè", "handle accent");

// appendToResourcePath
$t->diag('->appendToResourcePath');
$methodBase->setResourcePath('');
$methodBase->appendToResourcePath('test');
$t->is($methodBase->getResourcePath(), '/test');
$methodBase->appendToResourcePath('toto');
$t->is($methodBase->getResourcePath(), '/test/toto');
$methodBase->appendToResourcePath(array('titi', 'tutu'));
$t->is($methodBase->getResourcePath(), '/test/toto/titi/tutu', 'Simple array add multiple parameters');

// generateResourcePath
$t->diag('->generateResourcePath');
$methodBase = new GoogleApiMethodBase();
$methodBase->setService($service);

$methodBase->setResourcePath('/toto/titi');
$t->is(
  $methodBase->generateUrl(),
  'http://exemple.service.com/test.json/exemple/toto/titi', "this return correct resource path");

// Is required resource throw exception
$methodBase->addRequiredResourceOption('my_option');

try {
  $methodBase->generateUrl();
  $t->fail("Mandatory option throw exception");
} catch (Exception $e) {
  $t->pass("Mandatory option throw exception");
}

// Format of generated URL
$methodBase = new GoogleApiMethodBase();
$methodBase->setService($service);
$t->is($methodBase->generateUrl(), "https://www.googleapis.com/tasks/v1");
$methodBase->setResourcePath("/users/@me/lists");
$t->is($methodBase->generateUrl(), "https://www.googleapis.com/tasks/v1/users/@me/lists");
