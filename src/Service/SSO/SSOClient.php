<?php
namespace App\Service\SSO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

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
    const SSO_ORIGIN        = 'http://site.local';
    const SSO_API_KEY       = '3ada8f87cef4d41dbb385e41d0d55305b649161b';
    const SSO_COOKIE_NAME   = 'sso';
    
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
        $headers[]  = 'ApiKey: ' . self::SSO_API_KEY;
        $headers[]  = 'AuthVersion: ' . self::SSO_AUTH_VERSION;
        $headers[]  = 'Origin: ' . self::SSO_ORIGIN;
        
        curl_setopt($this->resource, CURLOPT_HEADERFUNCTION, function($ch, $headerLine){
            if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $matchCookie) == 1){
                $headerCookie = explode('=',$matchCookie[1]);
                if (count($headerCookie) == 2) {
                    SSOClient::$cookies[$headerCookie[0]] = $headerCookie[1];
                }
            }
            return strlen($headerLine);
        });
        
        $data = [
            'username' => $this->objRequest->get('username'),
            'password' => $this->objRequest->get('password')
        ];
        
        $this->setCurlOption(CURLOPT_URL, self::SSO_LOGIN)
             ->setCurlOption(CURLOPT_USERAGENT, self::SSO_USER_AGENT)
             ->setCurlOption(CURLOPT_RETURNTRANSFER, true)
             ->setCurlOption(CURLOPT_CUSTOMREQUEST, self::METHOD_POST)
             ->setCurlOption(CURLOPT_TIMEOUT, 30)
             ->setCurlOption(CURLOPT_POSTFIELDS, http_build_query($data))             
             ->setHeaders($headers)
        ;
        
        $retorno = $this->exec();
        if($this->getInfo(CURLINFO_HTTP_CODE) === 200){
            $userData = json_decode($retorno, true);
            $objSession = $this->objRequest->getSession();
            $objSession->set('userData', json_decode($retorno));
            
            
            $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
            $now->modify('+ 1 day');
            
            $objCookie = new Cookie(self::SSO_COOKIE_NAME, $userData['AccessToken'], $now->getTimestamp(), '/', 'site.local');
            $objResponse = new RedirectResponse('/home',302);
            $objResponse->headers->setCookie($objCookie);
        } else {
            $objResponse = new RedirectResponse('/teste',302);
        }
        return $objResponse;
    }
    
    private function getCookie(){
        $arrayCookies = $this->objRequest->cookies;
        if(count($arrayCookies) && array_key_exists(self::SSO_COOKIE_NAME, $arrayCookies)){
            return $arrayCookies[self::SSO_COOKIE_NAME];
        }
        return false;
    }
    
    public function getInfo(int $info = 0)
    {
        if($info){
            return curl_getinfo($this->resource, $info);
        }
        
        return curl_getinfo($this->resource);
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

