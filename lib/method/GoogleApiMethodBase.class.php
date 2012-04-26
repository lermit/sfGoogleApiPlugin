<?php

class GoogleApiMethodBase
{
  protected static $allowed_method = array(
    'GET',
    'POST',
    'PUT',
    'DELETE');
  protected
    $resource_path              = null,
    $resource_options           = array(),
    $resource_options_data      = array(),
    $resource_options_required  = array(),
    $method                     = "GET",
    $parameters                 = array(),
    $service                    = null;
    // TODO Think to add scope

  /**
   * __construct
   *
   * @param array $resource_options Resource Options data
   */
  function __construct(array $resource_options_data = array())
  {
    if( is_array( $resource_options_data ) )
    {
      $this->resource_options_data = $resource_options_data;
    }

    $this->configure();
  }

  public function configure()
  {
  }

  /**
   * Set corresponding Google API Service.
   *
   * @param GoogleApiServiceBase $service The google service
   *
   * @return GoogleApiMethodBase $this
   */
  public function setService($service)
  {
    if( ! $service instanceof GoogleApiServiceBase )
    {
      throw new Exception("You must profide a instance of GoogleApiServiceBase");
    }
    $this->service = $service;

    return $this;
  }

  /**
   * Get corresponding Google API Service.
   *
   * @return GoogleApiServiceBase The google service
   */
  public function getService()
  {

    return $this->service;
  }

  /**
   * Get resource Path.
   *
   * @return String Resource path
   */
  public function getResourcePath()
  {

    return $this->resource_path;
  }

  /**
   * Set resource path (like lists, create, etc ...)
   *
   * @param String $resource_path The resource path
   *
   * @return GoogleApiMethodBase Current object
   */
  public function setResourcePath($resource_path)
  {
    $this->resource_path = $resource_path;

    return $this;
  }

  /**
   * Append one or more resource to resource path.
   * If resources to append is an array all resources will be add.
   * Initial slash (/) will be automaticaly add.
   *
   * @param mixed $resources Data to add
   *
   * @return GoogleApiMethodBase $this
   */
  public function appendToResourcePath($resources)
  {
    if ( is_array($resources) )
    {
      foreach( $resources as $resource )
      {
        $this->appendToResourcePath($resource);
      }
    }
    else
    {
      sprintf("%s/%s", $this->resource_path, $resources);
    }
  }

  /**
   * Add a required resource option.
   *
   * @param String $option_name Option name
   *
   * @return GoogleApiMethodBase Current object
   */
  public function addRequiredResourceOption($option_name)
  {
    if ( ! in_array( $option_name, $this->resource_options_required ) )
    {
      $this->resource_options_required[] = $option_name;
      $this->addOption($option_name);
    }

    return $this;
  }

  /**
   * Add a resource option.
   *
   * @param String $option_name Option name
   *
   * @return GoogleApiMethodBase Current object
   */
  public function addOption($option_name)
  {
    if ( ! in_array( $option_name, $this->resource_options) )
    {
      $this->resource_options[] = $option_name;
    }

    return $this;
  }

  /**
   * Set method parameters. Previous parameter can be overload be won't be delete.
   *
   * @param array $parameters Associative array of parameters
   *
   * @return GoogleApiMethodBase Current object
   */
  public function setParameters(array $parameters = array())
  {
    if( !is_array( $parameters ) )
    {
      throw new Exception("You should provide a array as first parameter");
    }

    foreach($parameters as $key => $value)
    {
      $this->setParameter($key, $value);
    }

    return $this;
  }

  /**
   * Set a service parameter.
   *
   * @param String $name Name of the parameter
   * @param String $value Value of the parameter
   *
   * @return GoogleApiMethodBase Current object
   */
  public function setParameter($name, $value)
  {
    $this->parameters[$name] = $value;
  }

  /**
   * Get specified parameter.
   *
   * @param String $name Name of desired parameter
   * @return GoogleApiMethodBase Current object
   */
  public function getParameter($name)
  {

    return $this->parameters[$name];
  }

  /**
   * Get all service parameters as array
   *
   * @return array Service parameters
   */
  public function getParameters()
  {

    return $this->parameters;
  }

  /**
   * Set HTTP Method to use.
   *
   * @param String $method HTTP method.
   *
   * @return GoogleApiMethodBase Current object
   */
  public function setMethod($method)
  {
    if ( ! in_array($method, self::$allowed_method) )
    {
      throw new Exception(sprintf('HTTP method "%s" not allowed',$method));
    }

    $this->method = $method;

    return $this;
  }

  /**
   * Get HTTP method to use.
   *
   * @return String HTTP method to use
   */
  public function getMethod()
  {

    return $this->method;
  }

  /**
   * Generate resource path. This resource path will be append to service URL in order to designate desired data
   * Can throw an exception if all required resources options are not set.
   *
   * @return String the resource path
   */
  public function generateResourcePath()
  {
    $this->validateResourceOptions();

    return $this->buildResourcePath();
  }

  /**
   * Generate full URL for access service
   *
   * @return String URL for access service
   */
  public function generateUrl()
  {

    return sprintf('%s%s',
      $this->service->getUrl(),
      $this->generateResourcePath());
  }

  /**
   * Validate that all required resource options are set.
   *
   * @return void
   * @throw Exception
   */
  protected function validateResourceOptions()
  {
    foreach( $this->resource_options_required as $required_option )
    {
      if ( ! in_array( $required_option, $this->resource_options ) )
      {
        throw new Exception('Required resource option "%s" is not set', $required_option);
      }
    }
  }

  /**
   * Build resource path regarding options and resource "template" path.
   *
   * @return String Resource path
   */
  protected function buildResourcePath()
  {
    $new_resource_path = $this->resource_path;
    foreach($this->resource_options as $option)
    {
      $new_resource_path = strtr(
        $new_resource_path,
        sprintf('%%%%%s%%%%',$option),
        $this->resource_options[$option]
      );
    }

    return $new_resource_path;
  }
}
