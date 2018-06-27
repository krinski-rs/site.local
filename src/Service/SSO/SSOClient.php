<?php
namespace App\Service\SSO;

use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author krinski
 *        
 */
class SSOClient
{
    private $resource       = NULL;
    private $objRequest     = NULL;
    public static $cookies  = array();
    
    const METHOD_POST       = 'POST';
    const METHOD_GET        = 'GET';
    const METHOD_PUT        = 'PUT';
    const METHOD_PATCH      = 'PATCH';
    const METHOD_DELETE     = 'DELETE';
    
    const SSO_LOGIN         = 'http://sso.local/auth/login';
    const SSO_USER_AGENT    = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
    const SSO_AUTH_VERSION  = 'V1';
    const SSO_API_KEY       = 'aqui deve ser colocado a api fornecida.';
    const SSO_COOKIE_NAME   = 'aqui deve ser colocado o cookie do sso.';
    
    public function __construct(Request $objRequest)
    {
        $this->objRequest = $objRequest;
        $this->resource = curl_init();
        if(!$this->resource){
            throw new \RuntimeException('Erro ao iniciar a conexão.');
        }
    }
    
    public function login()
    {
        $this->reset();
        
        $headers = [];
        $ssoCookie = $this->getCookie();
        if($ssoCookie){
            $headers[] = 'cookie: ' . self::SSO_COOKIE_NAME . '=' . $ssoCookie;
        }
        $headers[]  = 'AppKey: ' . self::SSO_API_KEY;
        $headers[]  = 'AuthVersion: ' . self::SSO_AUTH_VERSION;
        
        curl_setopt($this->resource, CURLOPT_HEADERFUNCTION, function($ch, $headerLine){
            if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $matchCookie) == 1){
                $headerCookie = explode('=',$matchCookie[1]);
                if (count($headerCookie) == 2) {
                    SSOClient::$cookies[$headerCookie[0]] = $headerCookie[1];
                }
            }
            return strlen($headerLine);
        });
        $this->setCurlOption(CURLOPT_URL, self::SSO_LOGIN)
             ->setCurlOption(CURLOPT_USERAGENT, self::SSO_USER_AGENT)
             ->setCurlOption(CURLOPT_RETURNTRANSFER, true)
             ->setCurlOption(CURLOPT_CUSTOMREQUEST, self::METHOD_POST)
             ->setCurlOption(CURLOPT_TIMEOUT, 30)
             ->setHeaders($headers)
        ;
        
        
        $retorno = $this->exec();
        print_r($retorno);
    }
    
    private function getCookie(){
        $arrayCookies = $this->objRequest->cookies;
        if(count($arrayCookies) && array_key_exists(self::SSO_COOKIE_NAME, $arrayCookies)){
            return $arrayCookies[self::SSO_COOKIE_NAME];
        }
        return false;
    }
    
    public function exec()
    {
        return curl_exec($this->resource);
    }
    
    public function reset():SSOClient
    {
        curl_reset($this->resource);
        return $this;
    }
    
    public function setHeaders(array $headers):SSOClient
    {
        if(count($headers)){
            curl_setopt($this->resource, CURLOPT_HTTPHEADER, $headers);
        }
        return $this;
    }
    
    public function setCurlOption($key, $value):SSOClient
    {
        curl_setopt($this->resource, $key, $value);
        if($key === CURLOPT_RETURNTRANSFER){
            $this->returnTransfer = $value;
        }
        return $this;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function __destruct()
    {
        if($this->resource){
            curl_close($this->resource);
        }
    }
}

