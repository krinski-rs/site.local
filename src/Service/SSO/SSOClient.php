<?php
namespace App\Service\SSO;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @author krinski
 *        
 */
class SSOClient
{
    private $resource       = NULL;
    private $objRequest     = NULL;
    public static $cookies  = [];
    
    const METHOD_POST       = 'POST';
    const METHOD_GET        = 'GET';
    const METHOD_PUT        = 'PUT';
    const METHOD_PATCH      = 'PATCH';
    const METHOD_DELETE     = 'DELETE';
    
    const SSO_LOGIN         = 'http://sso.local.com/auth/login';
    const SSO_ME            = 'http://sso.local.com/auth/me';
    const SSO_LOGOUT        = 'http://sso.local.com/auth/logout';
    const SSO_USER_AGENT    = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
    const SSO_AUTH_VERSION  = 'V1';
    const SSO_ORIGIN        = 'http://site.local';
    const SSO_API_KEY       = '3ada8f87cef4d41dbb385e41d0d55305b649161b';
    const SSO_COOKIE_NAME   = 'sso';
    const SSO_COOKIE_DOMAIN = 'local.com';
    
    public function __construct(RequestStack $objRequestStack)
    {
        $this->objRequest = $objRequestStack->getCurrentRequest();
        $this->resource = curl_init();
        if(!$this->resource){
            throw new \RuntimeException('Erro ao iniciar a conexão.');
        }
    }
    
    public function isLoggedIn()
    {
        if(!($this->objRequest->getSession() instanceof Session) || !$this->objRequest->getSession()->has('userData')){
            return false;
        }
        
        if(!$this->getCookie()){
            return false;
        }
        
        
        $userData = $this->objRequest->getSession()->get('userData');
        if(!is_array($userData)){
            return false;
        }
        
        if(!array_key_exists('id', $userData) && !(integer)$userData['id']){
            return false;
        }
        
        return true;
    }
    
    public function logout()
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
            
        $retorno = $this->setCurlOption(CURLOPT_URL, self::SSO_LOGOUT)
            ->setCurlOption(CURLOPT_USERAGENT, self::SSO_USER_AGENT)
            ->setCurlOption(CURLOPT_RETURNTRANSFER, true)
            ->setCurlOption(CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE)
            ->setCurlOption(CURLOPT_TIMEOUT, 30)
            ->setHeaders($headers)
            ->exec();
        if(!$this->getInfo(CURLINFO_HTTP_CODE) === 200){
            throw new \Exception('Erro ao executar o logout.');
        }
        $objResponse = new RedirectResponse('/login',302);
        $objResponse->headers->clearCookie(self::SSO_COOKIE_NAME, '/', self::SSO_COOKIE_DOMAIN);
        return $objResponse;
    }
    
    public function me()
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
        
        $retorno = $this->setCurlOption(CURLOPT_URL, self::SSO_ME)
             ->setCurlOption(CURLOPT_USERAGENT, self::SSO_USER_AGENT)
             ->setCurlOption(CURLOPT_RETURNTRANSFER, true)
             ->setCurlOption(CURLOPT_CUSTOMREQUEST, self::METHOD_GET)
             ->setCurlOption(CURLOPT_TIMEOUT, 30)
             ->setHeaders($headers)
             ->exec();
         if($this->getInfo(CURLINFO_HTTP_CODE) === 200){
            return true;
        }
        return false;
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
             ->setHeaders($headers);
        
        $retorno = $this->exec();
        if($this->getInfo(CURLINFO_HTTP_CODE) === 200){
            $userData = json_decode($retorno, true);
            $objSession = $this->objRequest->getSession();
            $objSession->set('userData', json_decode($retorno));
            
            $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
            $now->modify('+ 1 day');
            
            $objCookie = new Cookie(self::SSO_COOKIE_NAME, $userData['AccessToken'], $now->getTimestamp(), '/', self::SSO_COOKIE_DOMAIN);
            $objResponse = new RedirectResponse('/home',302);
            $objResponse->headers->setCookie($objCookie);
        } else {
            $objResponse = new RedirectResponse('/login',302);
        }
        return $objResponse;
    }
    
    private function getCookie(){
        $arrayCookies = $this->objRequest->cookies->all();
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

