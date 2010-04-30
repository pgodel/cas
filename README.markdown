# Basic CAS PHP 5.3 client

The CAS client for PHP 5.3 is an attempt to have a small and simple client to interact with CAS. It may not support all features.

For more information about CAS visit http://www.ja-sig.org/wiki/display/CAS/Home

## Use

    require_once 'CAS/Client.php';

    $casUrl = 'https://cas.example.edu/cas';

    try
    {


      $client = new CAS\Client($casUrl);

      $this->client = $client;

      $client->setServiceUrl($serviceUrl);
      $client->setTicket($ticketId);

      $rsp = $client->validate();


      echo "\n".print_r($rsp->getIsPrimaryAccount(), true );
      echo "\n".print_r($rsp->getUser(), true );
      echo "\n".print_r($rsp->getUserAttributes(), true );
      echo "\n".print_r($rsp->getGroups(), true );
    }
    catch(Exception $ex)
    {
      echo "Error: ".$rsp->getAuthenticationFailure();
    }



## Requirements

* PHP 5.3.x

## Known issues

* Not all features may be supported.

## TODO

* Go over spec and implement reset of features