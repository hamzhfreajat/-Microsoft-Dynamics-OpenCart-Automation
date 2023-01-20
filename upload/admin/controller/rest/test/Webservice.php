<?php require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestAXApiProductAdmin extends Controller {

    public function index()  {
        $url = 'http://myIISServer.com/xmlservice?wsdl';

        // we unregister the current HTTP wrapper
                stream_wrapper_unregister('http');

        // we register the new HTTP wrapper
                stream_wrapper_register('http', 'MyServiceProviderNTLMStream') or die("Failed to register protocol");

        // so now all request to a http page will be done by MyServiceProviderNTLMStream.
        // ok now, let's request the wsdl file
        // if everything works fine, you should see the content of the wsdl file
                $client = new SoapClient($url, $options);

        // but this will failed
                $client->mySoapFunction();

        // restore the original http protocole
                stream_wrapper_restore('http');
    }

}
