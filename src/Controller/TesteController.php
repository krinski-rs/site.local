<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SSO\SSOClient;

class TesteController extends Controller
{
    
    public function index(Request $objRequest)
    {
        try {            
            $helper = $this->get('security.authentication_utils');
            return $this->render(
                'login.html.twig',
                [ ]
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function auth(Request $objRequest)
    {
        try {
            $objSSOClient = new SSOClient($objRequest);
            return $objSSOClient->login();
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    
    public function home(Request $objRequest)
    {
        try {
            return $this->render(
                'site.html.twig',
                [ ]
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

}

