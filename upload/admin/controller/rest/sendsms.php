<?php  require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestSendsms extends RestController{

   public function sendsms(){
   $get_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_sms` where customer_id between 6401 and 6500 ")->rows;

  //  $get_query = $this->db->query("SELECT distinct `telephone` FROM `" . DB_PREFIX . "order` where order_status_id='0' AND telephone REGEXP '^[+966]'")->rows;

    foreach($get_query as $key=>$value){
      $telephone = $value['telephone'];
      $firstname = $value['firstname'];
      $order_id = $value['order_id'];

      // echo "<pre>".'#'.$key .'#';
      //  var_dump($telephone);
      // echo "</pre>";

    if(isset($telephone) && $telephone !='' ){
      // Generate random verification code
    //  $rand_no = rand(10000, 99999);
    //  $query = $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET activation_code = '" . $rand_no . "', verified = 0 WHERE telephone like '%" . $telephone . "%'");
    //	$manufacturer_id = $this->db->getLastId();

    // :::::::::::::: SEND SMS API FOR USER PHONE :::::::::::::
    $sms_message1 ="لا تفوتك عروض الجمعة البيضاء من الجوف الزراعية تصل إلى 50% aljouf.com !";
    $sms_message = urlencode($sms_message1);

/*  $find1 = array(
        '&amp;',
        ' '
        );

  $replace1 = array(
      '&amp;' =>'&',
      ' ' =>'%20'
    );*/

    $find = array(
    '&amp;',
    ' '
    );

		$replace = array(
  		'&amp;' =>'&',
      ' ' =>'%20'
  		);

/*
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://basic.unifonic.com/wrapper/sendSMS.php?userid=DAhmed@aljouf.com.sa&password=Aljouf@2030&msg='.$sms_message.'&to='.$telephone.'&sender=Aljouf-AD&encoding=UTF8',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
*/


    /*
      //  $tmdsmsurl ="http://basic.unifonic.com/wrapper/sendSMS.php?userid=DAhmed@aljouf.com.sa&password=Aljouf@2030&msg=".$sms_message."&to=".$telephone."&sender=Aljouf&encoding=UTF8";


     $format = str_replace(array("\r\n", "\r", "\n"), '', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '', trim(str_replace($find, $replace, $tmdsmsurl))));
      $url = $format;
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $ssl=$this->config->get('tmdsms_ssl');
      if($ssl == 1){
      	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 3);
      }
      $curl_scraped_page = curl_exec($ch);
      curl_close($ch);
      */
      echo "<pre>";
      var_dump($response);
      echo "</pre>";


         }

       }

    }



  public function checking_function(){
// ::::::::::: report for orders

  //  $query = $this->db->query("SELECT COUNT(*) AS total, SUM(o.total) AS amount,order_id, c.iso_code_2 FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "country` c ON (o.payment_country_id = c.country_id) WHERE o.order_status_id > '0' GROUP BY o.payment_country_id");

    // return $query->rows;

     $get_query = $this->db->query("SELECT date_added , order_id ,payment_code, total FROM `" . DB_PREFIX . "order` where date_added BETWEEN '2021-11-23 00:00:00' AND '2021-12-05 23:59:59'  order by date_added asc")->rows;

    foreach($get_query as $key => $value){

      $get_total_product = $this->db->query("SELECT SUM(quantity) AS total_items FROM `" . DB_PREFIX . "order_product` where order_id = '$value[order_id]' order by order_id asc")->rows;

     foreach($get_total_product as $keyz => $valuez);

$json = array(
  "date_added" => $value['date_added'],
  "order_id" => $value['order_id'],
  "total" => $value['total'],
  "total_items" => $valuez['total_items'],
  "payment_code" => $value['payment_code'],
);

// echo $valuez['total_items'];

        $lrk= $valuez . $value;
  //       var_dump($lrk);

      echo "<pre>";
        echo $maxy = json_encode($json);
      echo "</pre>";


      // $customer_id = $value['customer_id'];
    }



/*

    $get_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` where telephone REGEXP '^[966]'")->rows;
    //  $get_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` where order_status_id='2' ")->rows;

    foreach($get_query as $key => $value){

      $customer_id = $value['customer_id'];
      $order_id = $value['order_id'];
      $model = $value['model'];
      $telephone = $value['telephone'];

      // $get_query2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` where order_id='$order_id'")->rows;

    $remax =substr($telephone,-9,9);
    //$trim = trim($telephone,$telephone[0]);

    if(isset($get_query) && $get_query !='' ){

    //  $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET telephone = '" . $remax . "' WHERE customer_id = '" . $customer_id . "'");


       echo "<pre>".'#'.$key .'#'.  $telephone .' // '. $remax;
      //  var_dump($get_query);
       echo "</pre>";


         }

       }
*/
    }

 }

 ?>
