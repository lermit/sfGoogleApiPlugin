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
    $data           = array(),
    $sf_user        = null;

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
    if($name === 'expires_in')
    {
      $this->setExpiresIn($value);
    }
    else
    {
      $this->data[$name] = $value;
    }

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

  /**
   * Set symfony user. 
   * 
   * @param sfUser $sf_user 
   *
   * @return GoogleAPIOAuthClient Current object
   */
  public function setSfUser(sfUser $sf_user)
  {
    if ( ! $sf_user instanceof sfUser )
    {
      throw new Exception("First parameter must be a sfUser object");
    }
    $this->sf_user = $sf_user;

    return $this->sf_user;
  }

  /**
   * Get symfony user.
   * 
   * @return sfUser The user
   */
  public function getSfUser()
  {

    return $this->sf_user;
  }

  protected function fillSfUser()
  {
    if( array_key_exists('access_token', $this->data) )
    {
      $this->getSfUser()->setAccessToken($this->data['access_token']);
    }

    if( array_key_exists('token_type', $this->data))
    {
      $this->getSfUser()->setTokenType($this->data['token_type']);
    }

    if( array_key_exists('expiration_date', $this->data) )
    {
      $this->getSfUser()->setAccessTokenExpirationDate($data['expiration_date']);
    }
  }

  /**
   * Set number of second the token will be valid. This time will be save regarding current time.
   * 
   * @param integer $expires_in Number of second
   * @access public
   * @return GoogleAPIOAuthClient Current object
   */
  public function setExpiresIn($expires_in)
  {
    if( ! is_numeric($expires_in))
    {
      throw new Exception("First parameter must be an interger");
    }

    // We minus one to expiration date for avoid some latency problem
    $this->data['expiration_date'] = (time()+$expires_in-1);

    return $this;
  }
}
