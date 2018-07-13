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
                [
                    'title' => 'xupem pausen'
                ]
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
                [
                    'title'     => 'xupem pausen',
                    'top'       => [
                        'logo'  => '/img/logo.png'
                    ],
                    'menuTop'     => [
                        [
                            'labelClass'    => 'label-success',
                            'labelTotal'    => '0',
                            'class'         => 'dropdown-messages',
                            'icon'          => 'icon-envelope-alt',
                            'subMenus'       => [
                                [
                                    'class' => 'dropdown-messages',
                                    'menu'  => [
                                        [
                                            'desc'          => 'John Smith',
                                            'info'          => 'Today',
                                            'text'          => 'Lorem ipsum dolor sit amet, consectetur adipiscing.',
                                            'label'         => 'Important',
                                            'labelClass'    => 'label-primary'
                                        ]
                                    ]
                                ],
                            ]
                        ],
                        [
                            'labelClass'    => 'label-danger',
                            'labelTotal'    => '1',
                            'class'         => 'dropdown-tasks',
                            'icon'          => 'icon-tasks',
                            'subMenus'       => [
                                'class' => 'dropdown-tasks',
                                'menu'  => []
                            ]
                        ],
                        [
                            'labelClass'    => 'label-info',
                            'labelTotal'    => '2',
                            'class'         => 'dropdown-alerts',
                            'icon'          => 'icon-comments',
                            'subMenus'       => [
                                'class' => 'dropdown-alerts',
                                'menu'  => []
                            ]
                        ],
                        [
                            'class'         => 'dropdown-user',
                            'icon'          => 'icon-user',
                            'subMenus'       => [
                                'class' => 'dropdown-user',
                                'menu'  => []
                            ]
                        ]
                    ],
                    'content'   => [
                        'title' => 'Dashboard'
                    ],
                    'footer'    => '<p>&copy;&nbsp;Reinaldo Krinski&nbsp;2018&nbsp;</p>'
                ]
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

}

