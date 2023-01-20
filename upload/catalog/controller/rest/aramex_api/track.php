<?php require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestAramexApiTrack extends RestController{
  public function index()  {

    //require_once('NTLMStream.php');
    require_once('NTLMSoapClient.php');

    $wsdl='https://ws.aramex.net/ShippingAPI.V2/Tracking/Service_1_0.svc?wsdl';
    $username = 'DAhmed@aljouf.com.sa';
    $password = 'FaMy@1983';
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// we unregister the current HTTP wrapper
    stream_wrapper_unregister('http');

// we register the new HTTP wrapper
stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocol");
// so now all request to a http page will be done by MyServiceProviderNTLMStream.
// ok now, let's request the wsdl file
//$soapClient = new NTLMSoapClient($wsdl, $username, $password);

//::::::::::::::::::::::: For Aramex ::::::::::::::::::::::::::::::::::::::::
//::::::::::::::::::::::: For Aramex ::::::::::::::::::::::::::::::::::::::::

if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
	//$base = $this->config->get('config_ssl');
	$base = HTTPS_SERVER;
} else {
	//$base = $this->config->get('config_url');
	$base = HTTP_SERVER;
}

$base = rtrim($base, "/");
$wsdlBasePath = $base . '/catalog/controller/rest/aramex_api/';
	
if($this->config->get('aramex_test')==1){
	$wsdlBasePath .='/TestMode';
}


$soapClient = new SoapClient($wsdlBasePath.'/Tracking.wsdl', array('trace' => 1));

//  print_r($wsdlBasePath);
	
	
//$soapClient = new SoapClient('Tracking.wsdl');
	echo '<pre>';
// shows the methods coming from the service 
	print_r($soapClient->__getFunctions());
      $types = $soapClient->__getTypes();
      foreach ($types as $type) {
          $type = preg_replace(
              array('/(\w+) ([a-zA-Z0-9]+)/', '/\n /'),
              array('<font color="green">${1}</font> <font color="blue">${2}</font>', "\n\t"),
              $type
          );
          echo $type;
          echo "\n\n";
      }


      /*
          parameters needed for the trackShipments method , client info, Transaction, and Shipments' Numbers.
          Note: Shipments array can be more than one shipment.
      */
	/*
	
	$params = array(
	'ClientInfo'  	=> array(
						'AccountCountryCode'	=> 'SA',
						'AccountEntity'		 	=> 'RUH',
						'AccountNumber'		 	=> '60521420',
						'AccountPin'		 	=> '432432',
						'UserName'			 	=> 'DAhmed@aljouf.com.sa',
						'Password'			 	=> 'FaMy@1983',
						'Version'			 	=> 'v1.0'
					),

	'Transaction' 	=> array(
						'Reference1'			=> '001' 
					),
	'Shipments'		=> array(
					'47429811144'
					)
);
 
	// calling the method and printing results
	try {
		$auth_call = $soapClient->TrackShipments($params);
	//	$results->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult;
		print_r($auth_call) ;
 	} catch (SoapFault $fault) {
		die('Error : ' . $fault->faultstring);
	}
	
	*/
	
	

	
if($this->config->get('shipping_aramex_allowed_cod') == 1){
    $account = ($this->config->get('shipping_aramex_cod_account_number')) ? $this->config->get('shipping_aramex_cod_account_number') : '';
    $pin = ($this->config->get('shipping_aramex_cod_account_pin')) ? $this->config->get('shipping_aramex_cod_account_pin') : '';
    $entity = ($this->config->get('shipping_aramex_cod_account_entity')) ? $this->config->get('shipping_aramex_cod_account_entity') : '';
    $country_code = ($this->config->get('shipping_aramex_cod_account_country_code')) ? $this->config->get('shipping_aramex_cod_account_country_code') : '';
}else{
    $account = ($this->config->get('shipping_aramex_account_number')) ? $this->config->get('shipping_aramex_account_number') : '';
    $pin = ($this->config->get('shipping_aramex_account_pin')) ? $this->config->get('shipping_aramex_account_pin') : '';
    $entity = ($this->config->get('shipping_aramex_account_entity')) ? $this->config->get('shipping_aramex_account_entity') : '';
    $country_code = ($this->config->get('shipping_aramex_account_country_code')) ? $this->config->get('shipping_aramex_account_country_code') : '';
}
    $username = ($this->config->get('shipping_aramex_email')) ? $this->config->get('shipping_aramex_email') : '';
    $password = ($this->config->get('shipping_aramex_password')) ? $this->config->get('shipping_aramex_password') : '';
    $client_info = array(
            'AccountCountryCode' => $country_code,
            'AccountEntity'      => $entity,
            'AccountNumber'      => $account,
            'AccountPin'         => $pin,
            'UserName'           => $username,
            'Password'           => $password,
            'Version'            => 'v1.0',
        );
        
        

  if(isset($this->request->post['track_awb']))
            {
                $awb_no = $this->request->post['track_awb'];
           /* }else{
                $awb_no = "47361046600";*/
            }
            
        $params = array(
                    'ClientInfo' 	=> $client_info,

                    'Transaction'	=> array(
                    'Reference1'		=> '001'
                    ),
                    'Shipments'		=> array(
                        $awb_no
                    )
                );
   
//::::::::::::::::::::AWB INPUT FORM :::::::::::::::::::::::::::::::::::

$formpost_url='https://aljouf.com/index.php?route=rest/aramex_api/tracking/shipment_tracking';
	
echo '<form method="POST" action="'.$formpost_url.'"><input type="text" name="track_awb" value="'.$awb_no.'" class="form-control"><button>Tracking</button></form>';
//::::::::::::::::::::AWB INPUT FORM :::::::::::::::::::::::::::::::::::

// calling the method and printing results
try {
    $results = $soapClient->TrackShipments($params);
    // print_r($results);
    // var_dump($results); 

      if($results->HasErrors){
                        if(count($results->Notifications->Notification) > 1){
                            $error="";
                            foreach($results->Notifications->Notification as $notify_error){
                                $data['eRRORS'][] = 'Aramex: ' . $notify_error->Code .' - '. $notify_error->Message."<br>";
                            }
                        }else{
                            $data['eRRORS'][] = 'Aramex: ' . $results->Notifications->Notification->Code . ' - '. $results->Notifications->Notification->Message;
                        }

                    }else{
                        if(isset($results->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY)){
                            $HAWBHistory = $results->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult;
                        }else{
                            $HAWBHistory = array();
                        }
                      //  :::::::::::::::::::::::::::::::::::::::::::::
                        
                    //    $data['html'] = $this->getTrackingInfoTable($HAWBHistory);
                    
                    
     $_resultTable = '<table border="1" summary="Item Tracking" class="table table-bordered table-hover">';
        $_resultTable .= '<thead><tr>
                          <td>Location</td>
                          <td>Action Date/Time</td>
                          <td class="a-right">Tracking Description</td>
                          <td class="a-center">Comments</td>
                          </tr>
                          </thead>';

        if(is_object($HAWBHistory)){
            $_resultTable .= '<tbody><tr>
                <td>' . $HAWBHistory->UpdateLocation . '</td>
                <td>' . $HAWBHistory->UpdateDateTime . '</td>
                <td>' . $HAWBHistory->UpdateDescription . '</td>
                <td>' . $HAWBHistory->Comments . '</td>
                </tr></tbody>';
        }else{
            foreach ($HAWBHistory as $HAWBUpdate) {
                $_resultTable .= '<tbody><tr>
                <td>' . $HAWBUpdate->UpdateLocation . '</td>
                <td>' . $HAWBUpdate->UpdateDateTime . '</td>
                <td>' . $HAWBUpdate->UpdateDescription . '</td>
                <td>' . $HAWBUpdate->Comments . '</td>
                </tr></tbody>';
            }
        }
        $_resultTable .= '</table>';

        echo $_resultTable;
                    
//:::::::::::::::::::::::::::::::::::::::::::::::::::::
        }
            
	} catch (SoapFault $fault) {
		die('Error : ' . $fault->faultstring);
	}
	
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      
 /*
            try{
                $results = $client->TrackShipments($params);

                    if($results->HasErrors){
                        if(count($results->Notifications->Notification) > 1){
                            $error="";
                            foreach($results->Notifications->Notification as $notify_error){
                                $data['eRRORS'][] = 'Aramex: ' . $notify_error->Code .' - '. $notify_error->Message."<br>";
                            }
                        }else{
                            $data['eRRORS'][] = 'Aramex: ' . $results->Notifications->Notification->Code . ' - '. $results->Notifications->Notification->Message;
                        }

                    }else{
                        if(isset($results->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY)){
                            $HAWBHistory = $results->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult;
                        }else{
                            $HAWBHistory = array();
                        }
                      //  :::::::::::::::::::::::::::::::::::::::::::::
                        
                    //    $data['html'] = $this->getTrackingInfoTable($HAWBHistory);
                    
                    
     $_resultTable = '<table summary="Item Tracking"  class="table table-bordered table-hover">';
        $_resultTable .= '<thead><tr>
                          <td>Location</td>
                          <td>Action Date/Time</td>
                          <td class="a-right">Tracking Description</td>
                          <td class="a-center">Comments</td>
                          </tr>
                          </thead>';

        if(is_object($HAWBHistory)){
            $_resultTable .= '<tbody><tr>
                <td>' . $HAWBHistory->UpdateLocation . '</td>
                <td>' . $HAWBHistory->UpdateDateTime . '</td>
                <td>' . $HAWBHistory->UpdateDescription . '</td>
                <td>' . $HAWBHistory->Comments . '</td>
                </tr></tbody>';
        }else{
            foreach ($HAWBHistory as $HAWBUpdate) {
                $_resultTable .= '<tbody><tr>
                <td>' . $HAWBUpdate->UpdateLocation . '</td>
                <td>' . $HAWBUpdate->UpdateDateTime . '</td>
                <td>' . $HAWBUpdate->UpdateDescription . '</td>
                <td>' . $HAWBUpdate->Comments . '</td>
                </tr></tbody>';
            }
        }
        $_resultTable .= '</table>';

        echo $_resultTable;
                    
//:::::::::::::::::::::::::::::::::::::::::::::::::::::
        }
    } catch (Exception $e) {
        $response['type']='error';
        $response['error']=$e->getMessage();
    }
 print_r($response);
 */
// restore the original http protocole
stream_wrapper_restore('http');
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    }

}
