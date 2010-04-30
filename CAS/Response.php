<?php
namespace CAS;

class Response
{
  /**
   * Example:
   <cas:serviceResponse xmlns:cas='http://www.yale.edu/tp/cas'>
   <cas:authenticationSuccess>
   <cas:user>abc</cas:user>
   <cas:primary_account>true</cas:primary_account>
   <cas:userattributes>
   <cas:firstname>Foo</cas:firstname>
   <cas:lastname>Bar</cas:lastname>
   <cas:emailAlias>foo.bar@example.com</cas:emailAlias>
   <cas:prim_email_domain>example.com</cas:prim_email_domain>
   <cas:primary_account>true</cas:primary_account>
   </cas:userattributes>
   <cas:groups>
   <cas:group>Administrative</cas:group>
   <cas:group>Employees</cas:group>
   </cas:groups>
   </cas:authenticationSuccess>
   </cas:serviceResponse>
   *
   *
   *
   * @var string XML
   */
  private $xml;


  /**
   * simpleXml object
   */
  private $sxml;

  public function __construct($xml)
  {
    $this->setXml($xml);
  }

  /**
   * Sets XML string sent from CAS server
   * @param string $xml
   */
  public function setXml($xml)
  {
    $this->xml = $xml;

    $xml = str_replace( '<cas:', '<', $xml);
    $xml = str_replace( '</cas:', '</', $xml);

    // remove cas xml namespace so we can use simplexml
    $this->sxml = simplexml_load_string($xml);
  }

  /**
   * Get CAS XML
   *
   * @return string
   */
  public function getXml()
  {
    return $this->xml;
  }

  /**
   * Checks if authentication request was successful
   * @return bool
   */
  public function isAuthenticationSuccess()
  {
    return $this->sxml && $this->sxml->authenticationSuccess;
  }

  /**
   * Returns authentication failure error
   * @return string
   */
  public function getAuthenticationFailure()
  {
    return $this->sxml->authenticationFailure;
  }

  /**
   * Get User
   * @return string
   */
  public function getUser()
  {
    if (!$this->isAuthenticationSuccess())
    {
      throw new \Exception('Not authenticated');
    }

    return (string)$this->sxml->authenticationSuccess->user;
  }

  /**
   * Returns true if primary_account is true
   * @return bool
   */
  public function getIsPrimaryAccount()
  {
    if (!$this->isAuthenticationSuccess())
    {
      throw new \Exception('Not authenticated');
    }

    return (bool)$this->sxml->authenticationSuccess->primary_account;
  }

  /**
   * Get User Attributes. Returns object with following properties: firstname, lastname, emailAlias, prim_email_domain, primary_account
   * 
   * @return object
   */
  public function getUserAttributes()
  {
    if (!$this->isAuthenticationSuccess())
    {
      throw new \Exception('Not authenticated');
    }

    return $this->sxml->authenticationSuccess->userattributes;
  }

  /**
   * Get User Groups. Returns array with group names.
   * 
   * @return array
   */
  public function getGroups()
  {
    if (!$this->isAuthenticationSuccess())
    {
      throw new \Exception('Not authenticated');
    }

    $groups = (array)$this->sxml->authenticationSuccess->groups;

    return $groups['group'];
  }
}