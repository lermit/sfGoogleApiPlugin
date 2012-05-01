<?php

class GoogleAPIBrowser
{
  protected
    $method   = null,
    $user     = null,
    $browser  = null;

  function __construct($method = null, $user = null)
  {
    //TODO
  }

  /**
   * Set Google REST Method to execute. (eg : GoogleAPIMethodTaskListGet, ...)
   *
   * @param GoogleApiMethodBase $method
   * @access public
   * @return GoogleAPIBrowser Current object
   */
  public function setMethod(GoogleApiMethodBase $method)
  {
    if ( ! $method instanceof GoogleApiMethodBase )
    {
      throw new Exception("You have to pass a GoogleApiMethod");
    }

    $this->method = $method;

    return $this;
  }

  /**
   * Get Google API Method to execute.
   *
   * @access public
   * @return GoogleApiMethodBase Method to execute
   */
  public function getMethod()
  {

    return $this->method;
  }

  /**
   * Set connected Google Client (via oauth2).
   *
   * @param GoogleAPIOAuthClient $user
   * @access public
   * @return GoogleAPIBrowser Current objectj
   */
  public function setUser(SfGoogleUser $user)
  {
    if ( ! $user instanceof SfGoogleUser)
    {
      throw new Exception("You have to pass a SfGoogleUser");
    }

    $this->user = $user;

    return $this;
  }

  /**
   * Get connect Google Client.
   *
   * @access public
   * @return GoogleAPIOAuthClient Google client
   */
  public function getUser()
  {

    return $this->user;
  }

  /**
   * Set browser. We used the sfWebBrowserPlugin so please install this one.
   *
   * @param sfWebBrowser $browser
   *
   * @return GoogleAPIBrowser Current object
   */
  public function setBrowser(sfWebBrowser $browser)
  {
    if ( ! $browser instanceof sfWebBrowser )
    {
      throw new Exception("You have to pass an instance of sfWebBrowser");
    }

    return $this;
  }

  /**
   * Get current browser object. This method create a browser if no one is set.
   *
   * @access public
   * @return sfWebBrowser Current browser
   */
  public function getBrowser()
  {

    if ( $this->browser == null )
    {
      $this->browser = new sfWebBrowser();
    }

    return $this->browser;
  }

  /**
   * Execute method.
   *
   * @access public
   * @return array() Result
   */
  public function execute()
  {
    $this->authenticate();
    $this->call();

    /**
     * We maybe need to reauthenticate user 
     */
    if($this->getBrowser()->getResponseCode() != 200)
    {
      $this->authenticate();
      $this->call();
    }

    if($this->getBrowser()->getResponseCode() == 200)
    {
      
      return true;
    }
    else
    {

      return false;
    }

  }

  protected function call()
  {
    $this
      ->getBrowser()
      ->call(
        $this->method->generateUrl(),
        $this->method->getMethod(),
        $this->method->getParameters(),
        $this->getHeader());

    return $this;
  }

  public function authenticate()
  {
    if(!$this->getUser()->isAuthenticated() ||
      ($this->getBrowser()->getResponseCode() !== "" && $this->getBrowser()->getResponseCode() != 200))
    {
      $this->processAuthenticate();
    } 

    return $this;
  }

  protected function processAuthenticate()
  {
    sfContext::getInstance()->getRequest()->setParameter("scopes", $this->getMethod()->getScopes());
    sfContext::getInstance()->getController()->forward("oauth", "signIn");
  }

  protected function getHeader()
  {
    return array(
      'Authorization' => sprintf("%s %s",
        $this->user->getTokenType(),
        $this->user->getAccessToken()),
    );
  }

}
