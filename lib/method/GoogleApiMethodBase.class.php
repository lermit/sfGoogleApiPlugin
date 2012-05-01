<?php

class GoogleApiMethodBase
{
  protected static $allowed_method = array(
    'GET',
    'POST',
    'PUT',
    'DELETE');
  protected
    /**
     * The resource path pattern like /tasks/%%id%%
     */
    $resource_path              = null,
    /**
     * User options (associative array)
     */
    $options                    = array(),
    /**
     * Required options
     */
    $resource_options_required  = array(),
    /**
     * Additionnal parameter
     */
    $additionnal_parameters     = null,
    /**
     *  HTTP method to use
     */
    $method                     = "GET",
    /**
     * An array for HTTP parameter -- eg : after question mark or in request body -- Can, may be, be deleted.
     */
    $parameters                 = array(),
    /**
     * Google Services like.
     */
    $service                    = null,
    /**
     * Google scope required by method.
     */
    $scopes                     = array();

  /**
   * __construct
   *
   * @param array $resource_options Resource Options data
   */
  function __construct(array $options = array())
  {
    if( is_array( $options ) )
    {
      $this->options = $options;
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
      throw new Exception("You must provide a instance of GoogleApiServiceBase");
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
      $this->resource_path = sprintf("%s/%s", $this->resource_path, $resources);
    }

    return $this;
  }

  /**
   * Add a required resource option. If array is passed, all items will be add.
   *
   * @param mixed $option_name Option name or array of option name
   *
   * @return GoogleApiMethodBase Current object
   */
  public function addRequiredOption($option_name)
  {
    if ( is_array( $option_name ) )
    {
      foreach ( $option_name as $option )
      {
        $this->addRequiredOption($option);
      }
    }

    if ( ! in_array( $option_name, $this->resource_options_required ) )
    {
      $this->resource_options_required[] = $option_name;
    }

    return $this;
  }

  /**
   * Retourn an array of required options
   *
   * @return array Required options
   */
  public function getRequiredOptions()
  {

    return $this->resource_options_required;
  }

  /**
   * Add a resource option value.
   *
   * @param String $option_name Option name
   *
   * @return GoogleApiMethodBase Current object
   */
  public function addOption($option_name, $value)
  {
    // When options list is modify we have to re-compute additionnal_parameter so set this to null
    $this->additionnal_parameters = null;

    $this->options[$option_name] = $value;

    return $this;
  }

  /**
   * Add multiple options with an associative array.
   *
   * @param mixed $options An array of options
   *
   * @return GoogleApiMethodBase Current object
   */
  public function addOptions($options)
  {
    if(is_array($options))
    {
      foreach($options as $option_key => $option_value)
      {
        $this->addOption($option_key, $option_value);
      }
    }

    return $this;
  }

  /**
   * Get options specified by user.
   *
   * @return array Array of options
   */
  public function getOptions()
  {

    return $this->options;
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
   * Set required google scope for method
   *
   * @param array $scopes
   *
   * @return GoogleApiMethodBase Current object
   */
  public function setScopes($scopes = array())
  {
    if(is_array($scopes))
    {
      $this->scopes = $scopes;
    }
    else
    {
      $this->addScope($scopes);
    }

    return $this;
  }

  /**
   * Get all required google scope.
   *
   * @return array Array of google scope
   */
  public function getScopes()
  {

    return $this->scopes;
  }

  /**
   * Add a required scope
   *
   * @param string $scope Scope to add (like tasks, userinfo.email, etc. )
   * @return GoogleApiMethodBase Current object
   */
  public function addScope($scope)
  {
    $this->scope[] = $scope;

    return $this;
  }

  /**
   * Retourne additionnal parameters
   *
   * @return array Additionnals parameters
   */
  public function getAdditionnalParameters()
  {
    /**
     * Additionnal parameters are compute by executing generateUrl function
     */
    if($this->additionnal_parameters === null)
    {
      $this->generateUrl();
    }

    return $this->additionnal_parameters;
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
      if ( ! in_array( $required_option, $this->options ) )
      {
        throw new Exception(sprintf('Required resource option "%s" is not set', $required_option));
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
    $additionnal_parameters = $this->options;
    $new_resource_path = $this->resource_path;
    foreach($this->options as $option_key => $option_value)
    {
      $searched_patern = sprintf('%%%%%s%%%%', $option_key);
      if(strpos( $new_resource_path, $searched_patern ))
      {
        $new_resource_path = str_replace(
          $searched_patern,
          $option_value,
          $new_resource_path
        );

        unset($additionnal_parameter[$option_key]);
      }
    }

    $this->additionnal_parameters = $additionnal_parameters;

    return $new_resource_path;
  }
}
