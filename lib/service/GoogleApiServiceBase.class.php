<?php

/**
 * GoogleApiServiceBase
 *
 * @package sfGoogleApiPlugin
 * @author Romain THERRAT <romain42@gmail.com>
 * @copyright 2012 Romain THERRAT
 * @license LGPL http://www.gnu.org/copyleft/lesser.html
 * @version Release: 0.1
 */
class GoogleApiServiceBase
{
  protected
    $api_name     = null,
    $api_version  = null,
    $api_endpoint = null;

  function __construct()
  {
    $this->setApiEndPoint(
      sfConfig::get('app_google_api_endpoint', 'https://www.googleapis.com'));

    $this->configure();
  }

  public function configure()
  {
  }


  /**
   * Set Google API End Point. Can be configure into app_google_api_endpoint config parameter.
   * 
   * @param string $address API Endpoint address
   * @access public
   * @return GoogleApiServiceBase Current object
   */
  public function setApiEndPoint($address)
  {
    $this->api_endpoint = $address;

    return $this;
  }

  /**
   * Get Google API End Point.
   * 
   * @access public
   * @return String Endpoint address
   */
  public function getApiEndPoint()
  {
    
    return $this->api_endpoint;
  }

  /**
   * Set Google API Service Name.
   *
   * @param String $api_name Google API Service Name
   *
   * @return GoogleApiServiceBase Current object
   */
  public function setApiName($api_name)
  {
    $this->api_name = $api_name;

    return $this;
  }

  /**
   * Get Google API Service Name
   *
   * @return String Google API Service Name
   */
  public function getApiName()
  {

    return $this->api_name;
  }

  /**
   * Set Google API Service version.
   *
   * @param String $api_version Google API Service version
   *
   * @return GoogleApiServiceBase Current object
   */
  public function setApiVersion($api_version)
  {
    $this->api_version = $api_version;

    return $this;
  }

  /**
   * Get Google API version.
   *
   * @return String Google API version
   */
  public function getApiVersion()
  {

    return $this->api_version;
  }

  /**
   * Return generated URL for Google API service.
   *
   * @return String Generated URL
   */
  public function getUrl()
  {

    return sprintf("%s/%s/%s",
      $this->api_endpoint,
      $this->api_name,
      $this->api_version
    );
  }
}
