<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bridge\Monolog\Logger;
use App\Service\SSO\AuthExceptions;
use App\Service\SSO\SSOClient;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SSOAuthEventListener
{
    private $objLogger                  = NULL;
    private $objAuthExceptions          = NULL;
    private $objSSoClient               = NULL;
    private $objParameterBagInterface   = NULL;
    
    public function __construct(Logger $objLogger, AuthExceptions $objAuthExceptions, SSOClient $objSSoClient, ParameterBagInterface $objParameterBagInterface)
    {
        $this->objLogger                = $objLogger;
        $this->objAuthExceptions        = $objAuthExceptions;
        $this->objSSoClient             = $objSSoClient;
        $this->objParameterBagInterface = $objParameterBagInterface;
    }
    
    public function onKernelRequest(GetResponseEvent $objGetResponseEvent)
    {
        try {
            if ($objGetResponseEvent->getRequestType() !== HttpKernel::MASTER_REQUEST) {
                return;
            }
            
            if (in_array($objGetResponseEvent->getRequest()->get('_route'), ['login', 'auth'])) {
                return;
            }
            
            $allowAccess = $this->objAuthExceptions->allowUnauthorizedAccess($_SERVER['REMOTE_ADDR'], $objGetResponseEvent->getRequest()->get('_route'));
            if ($allowAccess || $this->objSSoClient->isLoggedIn()) {
                return;
            }
            
            if(!$this->objSSoClient->me()){
                throw new \Exception('Erro de login.');
            }
        } catch (\Exception $e) {
            $this->setRedirectToLoginResponse($objGetResponseEvent);
        }
    }
    
    private function setRedirectToLoginResponse(GetResponseEvent $objGetResponseEvent)
    {
        $request = Request::createFromGlobals();
        if ($request->isXmlHttpRequest() ) {
            $data = array("msg" => "Você precisa estar logado para realizar esta ação");
            $response = new JsonResponse($data, 403);
        } else {
            $response = new RedirectResponse('/login', 302);
        }
        $objGetResponseEvent->setResponse($response);
        $objGetResponseEvent->stopPropagation();
    }
    
    public function onKernelResponse(FilterResponseEvent $objFilterResponseEvent)
    {
        $request = $objFilterResponseEvent->getRequest();
        /*
         * Execute o CORS aqui para garantir que o domínio esteja no sistema
         */
        
        //if (in_array($request->headers->get('origin'), $this->cors)) {
        if (HttpKernelInterface::MASTER_REQUEST !== $objFilterResponseEvent->getRequestType()) {
            return;
        }
        
        $objResponse = $objFilterResponseEvent->getResponse();
        $objResponse->headers->set('Access-Control-Allow-Origin', '*');
        $objResponse->headers->set('Access-Control-Allow-Credentials', 'true');
        $objResponse->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');
        $objResponse->headers->set('Access-Control-Allow-Headers', implode(",", $this->objParameterBagInterface->get('cors')['allowed_headers']));
    }
}

