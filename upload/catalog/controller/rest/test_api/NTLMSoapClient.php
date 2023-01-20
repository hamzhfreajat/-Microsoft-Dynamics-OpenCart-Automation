<?php
class NTLMSoapClient extends SoapClient {

    protected $username;
    protected $password;

    function __construct (string $wsdl = null , string $username = '', string $password =''){
        $this->username = $username;
        $this->password = $password;

        $contextOpts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );
        $options = array(
            'login' => $this->username,
            'password' => $this->password,
            'soap_version'=>SOAP_1_1,
            'exceptions'=>true,
            'trace'=>1,
            'cache_wsdl'=>WSDL_CACHE_NONE,
            'stream_context' => stream_context_create($contextOpts)
        );

        parent::__construct($wsdl,$options);
    }

    function __doRequest($request, $location, $action, $version, $one_way = NULL) {

        $headers = array(
            'Method: POST',
            'Connection: Keep-Alive',
            'User-Agent: PHP-SOAP-CURL',
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "'.$action.'"',
        );

        $this->__last_request_headers = $headers;
        $ch = curl_init($location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    function __getLastRequestHeaders() {
        return implode("n", $this->__last_request_headers)."n";
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class NTLMStream {

    protected $user = 'aljouf\axservice';
    protected $password = 'Jadco@$ri@ad';


    private $path;
    private $mode;
    private $options;
    private $opened_path;
    private $buffer;
    private $pos;

    /**
     * Open the stream
     *
     * @param unknown_type $path
     * @param unknown_type $mode
     * @param unknown_type $options
     * @param unknown_type $opened_path
     * @return unknown
     */
    public function stream_open($path, $mode, $options, $opened_path) {

        $this->path = $path;
        $this->mode = $mode;
        $this->options = $options;
        $this->opened_path = $opened_path;

        $this->createBuffer($path);

        return true;
    }

    /**
     * Close the stream
     *
     */
    public function stream_close() {

        curl_close($this->ch);
    }

    /**
     * Read the stream
     *
     * @param int $count number of bytes to read
     * @return content from pos to count
     */
    public function stream_read($count) {

        if(strlen($this->buffer) == 0) {
            return false;
        }

        $read = substr($this->buffer,$this->pos, $count);

        $this->pos += $count;

        return $read;
    }
    /**
     * write the stream
     *
     * @param int $count number of bytes to read
     * @return content from pos to count
     */
    public function stream_write($data) {

        if(strlen($this->buffer) == 0) {
            return false;
        }
        return true;
    }

    /**
     *
     * @return true if eof else false
     */
    public function stream_eof() {


        if($this->pos > strlen($this->buffer)) {
            return true;
        }

        return false;
    }

    /**
     * @return int the position of the current read pointer
     */
    public function stream_tell() {

        return $this->pos;
    }

    /**
     * Flush stream data
     */
    public function stream_flush() {

        $this->buffer = null;
        $this->pos = null;
    }

    /**
     * Stat the file, return only the size of the buffer
     *
     * @return array stat information
     */
    public function stream_stat() {


        $this->createBuffer($this->path);
        $stat = array(
            'size' => strlen($this->buffer),
        );

        return $stat;
    }
    /**
     * Stat the url, return only the size of the buffer
     *
     * @return array stat information
     */
    public function url_stat($path, $flags) {

        $this->createBuffer($path);
        $stat = array(
            'size' => strlen($this->buffer),
        );

        return $stat;
    }

    /**
     * Create the buffer by requesting the url through cURL
     *
     * @param unknown_type $path
     */
    private function createBuffer($path) {
        if($this->buffer) {
            return;
        }


        $this->ch = curl_init($path);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($this->ch, CURLOPT_USERPWD, $this->user.':'.$this->password);
        $this->buffer = curl_exec($this->ch);


        $this->pos = 0;

    }
}

// // Authentification parameter
// class MyServiceNTLMSoapClient extends NTLMSoapClient {

// }
?>
