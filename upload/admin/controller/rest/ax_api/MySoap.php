<?php

class MySoap {

    private $WSDL = 'http://ax-test.aljouf.com.sa:8101/openCardAX/xppservice.svc?wsdl';

    private $USERNAME = 'dummy';
    private $PASSWORD = 'dummy';

    private $soapclient;

    private function localWSDL()
    {
        $local_file_name = 'local.wsdl';
        $local_file_path = 'path/to/file/'.$local_file_name;

        // Cache local file for 1 day
        if (filemtime($local_file_path) < time() - 86400) {

            // Modify URL to format http://[username]:[password]@[wsdl_url]
            $WSDL_URL = preg_replace('/^https:\/\//', "https://{$this->USERNAME}:{$this->PASSWORD}@", $this->WSDL);

            $wsdl_content = file_get_contents($WSDL_URL);
            if ($wsdl_content === FALSE) {

                throw new Exception("Download error");
            }

            if (file_put_contents($local_file_path, $wsdl_content) === false) {

                throw new Exception("Write error");
            }
        }

        return $local_file_path;
    }

    private function getSoap()
    {
        if ( ! $this->soapclient )
        {
            $this->soapclient = new SoapClient($this->localWSDL(), array(
                'login'    => $this->USERNAME,
                'password' => $this->PASSWORD,
            ));
        }

        return $this->soapclient;
    }

    public function callWs() {

        $this->getSoap()->wsMethod();
    }
}