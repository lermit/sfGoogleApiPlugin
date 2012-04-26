<?php

/**
 * GoogleAPIOAuthClient 
 * 
 * @package sfGoogleApiPlugin
 * @author Romain THERRAT <romain42@gmail.com> 
 * @copyright 2012 Romain THERRAT
 * @license LGPL http://www.gnu.org/copyleft/lesser.html
 * @version Release: 0.1
 */
class GoogleAPIOAuthClient
{
  protected
    $data           = array();
  
  function __construct(array $options = array())
  {
    if( is_array($options))
    {
      $this->setOptions($options);
    }

    var_dump($this->data);
  }

  /**
   * Set Options.
   * 
   * @param array $options Options to set
   *
   * @return GoogleAPIOAuthClient Current object
   */
  function setOptions(array $options)
  {
    foreach( $options as $key => $value )
    {
      $this->setOption($key, $value);
    }

    return $this;
  }

  /**
   * Set an option.
   * 
   * @param String $name Name of the option
   * @param String $value Value of the option
   * 
   * @return GoogleAPIOAuthClient Current object
   */
  function setOption($name, $value)
  {
    $this->data[$name] = $value;

    return $this;
  }

  /**
   * Get specified option.
   * 
   * @param String $name Name of the option
   *
   * @return mixed Option value
   */
  function getOption($name)
  {
    return $this->data[$name];
  }

  /**
   * Return true or false regarding if user is authenticated.
   * 
   * @return bool True if user is authenticated otherwise false
   */
  function isConnected()
  {
    return array_key_exists( 'access_token', $this->data);
  }

}
