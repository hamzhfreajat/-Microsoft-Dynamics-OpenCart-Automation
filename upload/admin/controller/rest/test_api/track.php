<?php require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestTestApiTrack extends RestController {
    public function index()  {

//        //require_once('NTLMStream.php');
//        require_once('NTLMSoapClient.php');
//
//        $wsdl='http://ax-test.aljouf.com.sa:8101/openCardr/xppservice.svc?wsdl';
//        $username = 'aljouf\axservice';
//        $password = 'Jadco@$ri@ad';
////::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//// we unregister the current HTTP wrapper
//        stream_wrapper_unregister('http');
//
//// we register the new HTTP wrapper
//        stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocol");
//// so now all request to a http page will be done by MyServiceProviderNTLMStream.
//// ok now, let's request the wsdl file
////$soapClient = new NTLMSoapClient($wsdl, $username, $password);
//
////::::::::::::::::::::::: For Aramex ::::::::::::::::::::::::::::::::::::::::
////::::::::::::::::::::::: For Aramex ::::::::::::::::::::::::::::::::::::::::
//
//        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
//            //$base = $this->config->get('config_ssl');
//            $base = HTTPS_SERVER;
//        } else {
//            //$base = $this->config->get('config_url');
//            $base = HTTP_SERVER;
//        }
//
//        $base = rtrim($base, "/");
//        $wsdlBasePath = $base . '/catalog/controller/rest/aramex_api/';
//
//        if($this->config->get('aramex_test')==1){
//            $wsdlBasePath .='/TestMode';
//        }
//
//
//        $soapClient = new NTLMSoapClient('http://ax-test.aljouf.com.sa:8101/openCardr/xppservice.svc?wsdl', $username , $password);
//
////  print_r($wsdlBasePath);
//
//
////$soapClient = new SoapClient('Tracking.wsdl');
//        echo '<pre>';
//// shows the methods coming from the service
//        print_r($soapClient->__getFunctions());
//        $types = $soapClient->__getTypes();
//        foreach ($types as $type) {
//            $type = preg_replace(
//                array('/(\w+) ([a-zA-Z0-9]+)/', '/\n /'),
//                array('<font color="green">${1}</font> <font color="blue">${2}</font>', "\n\t"),
//                $type
//            );
//            echo $type;
//            echo "\n\n";
//        }
//
//        $salesID = $soapClient -> CreateSOHeader();
//        $test = $soapClient -> TestReturnStr();
//        var_dump($salesID->response);
//        var_dump($test);
//        $param1 = array(
//            '_ItemId'		 	=> '01010410100004',
//        );
//
//        $getOnHand = $soapClient -> GetOnhand($param1);
//
//        var_dump($getOnHand);
//
//        for($i = 1 ; $i < 3 ; $i++){
//
//                $params = array(
//                    'salesID'	            =>  $salesID->response,
//                    '_ItemId'		 	=> '01010410100004',
//                    '_SalesQty'		 	=> 2,
//                    '_SalesPrice'		 	=> 300,
//                    '_ItemDiscount'			 	=> 0.0,
//                );
//
//                try {
//                    $newLine = $soapClient->CreateSOLineNew($params);
//                    var_dump($newLine);
//                } catch (SoapFault $fault) {
//                    die('Error : ' . $fault->faultstring);
//                }
//
//        }
//
//
//
////        var_dump($sale_id->response);
//
//
//
//        stream_wrapper_restore('http');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

        $json = '{"Peter":35,"Ben":37,"Joe":43}';
        return $json;

    }

}

