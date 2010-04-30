<?php
namespace CAS;

require_once __DIR__.'/Response.php';

class Client
{
  private $casUrl = 'https://cas.example.edu/cas';
  private $serviceUrl = 'https://example.com/cascheck';
  private $ticket = null;

  public function __construct($casUrl)
  {
    $this->casUrl = $casUrl;
  }


  /**
   *
   * @return string CAS Url
   */
  public function getCasUrl()
  {
    return $this->casUrl;
  }

  /**
   *
   * @param string $serviceUrl
   */
  public function setServiceUrl($serviceUrl)
  {
    $this->serviceUrl = $serviceUrl;
  }

  /**
   *
   * @return string Service Url
   */
  public function getServiceUrl()
  {
    return $this->serviceUrl;
  }

  /**
   *
   * @param string $ticket CAS ticket
   */
  public function setTicket($ticket)
  {
    $this->ticket = $ticket;
  }

  /**
   *
   * @return string CAS ticket
   */
  public function getTicket()
  {
    return $this->ticket;
  }

  /**
   *
   * @return string Login Url
   */
  public function getLoginUrl()
  {
    return $this->getCasUrl().'/login?service='.urlencode($this->getServiceUrl());
  }

  public function getLogoutUrl()
  {
    return $this->getCasUrl().'/logout?service='.urlencode($this->getServiceUrl());
  }

  /**
   *
   * @return string Validate Url
   */
  public function getValidateUrl()
  {
    $ticket = $this->getTicket();
    if (empty($ticket))
    {
      throw new \Exception('Invalid Ticket');
    }
    return $this->getCasUrl().'/serviceValidate?service='.urlencode($this->getServiceUrl()).'&ticket='.urlencode($ticket);
  }

  /**
   * Validates ticket and returns XML output
   * @return string XML output
   */
  public function validate()
  {
    if (!function_exists('curl_init'))
    {
      throw new \Exception('Curl support not found. Please install curl extension');
    }

    $url = $this->getValidateUrl();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);


    return new Response($output);

    return $output;
  }
}