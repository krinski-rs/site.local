<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    
    public function login(Request $objRequest)
    {
        try {            
            return $this->render(
                'login.html.twig',
                [
                    'title' => 'R&K'
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
            $objSSOClient = $this->get('sso_client');
            return $objSSOClient->login();
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    
    public function logout()
    {
        
        try {
            $objSSOClient = $this->get('sso_client');
            return $objSSOClient->logout();
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
                    'title'     => 'R&K',
                    'top'       => [
                        'logo'  => '/img/logo.png'
                    ],
                    'menuTop'     => [
                        [
                            'labelClass'    => 'label-success',
                            'labelTotal'    => '3',
                            'icon'          => 'icon-envelope-alt',
                            'subMenus'       => [
                                'class' => 'dropdown-messages',
                                'menu'  => [
                                    [
                                        'href'          => '#',
                                        'desc'          => 'John Smith',
                                        'info'          => 'Today',
                                        'text'          => 'Lorem ipsum dolor sit amet, consectetur adipiscing.',
                                        'label'         => 'Important',
                                        'labelClass'    => 'label-primary'
                                    ],
                                    [
                                        'href'          => '#',
                                        'desc'          => 'Raphel Jonson',
                                        'info'          => 'Yesterday',
                                        'text'          => 'Lorem ipsum dolor sit amet, consectetur adipiscing.',
                                        'label'         => 'Moderate',
                                        'labelClass'    => 'label-success'
                                    ],
                                    [
                                        'href'          => '#',
                                        'desc'          => 'Chi Ley Suk',
                                        'info'          => '26 Jan 2014',
                                        'text'          => 'Lorem ipsum dolor sit amet, consectetur adipiscing.',
                                        'label'         => 'Low',
                                        'labelClass'    => 'label-danger'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'labelClass'    => 'label-danger',
                            'labelTotal'    => '4',
                            'icon'          => 'icon-tasks',
                            'subMenus'       => [
                                'class' => 'dropdown-tasks',
                                'menu'  => [
                                    [
                                        'href'          => '#',
                                        'desc'          => 'Profile',
                                        'info'          => '40% Complete',
                                        'labelClass'    => 'progress-bar-success',
                                        'label'         => '40% Complete (success)',
                                    ],
                                    [
                                        'href'          => '#',
                                        'desc'          => 'Pending Tasks',
                                        'info'          => '20% Complete',
                                        'labelClass'    => 'progress-bar-info',
                                        'label'         => '20% Complete',
                                    ],
                                    [
                                        'href'          => '#',
                                        'desc'          => 'Work Completed',
                                        'info'          => '60% Complete',
                                        'labelClass'    => 'progress-bar-warning',
                                        'label'         => '60% Complete (warning)',
                                    ],
                                    [
                                        'href'          => '#',
                                        'desc'          => 'Summary',
                                        'info'          => '80% Complete',
                                        'labelClass'    => 'progress-bar-danger',
                                        'label'         => '80% Complete (danger)',
                                    ]
                                ]
                            ]
                        ],
                        [
                            'labelClass'    => 'label-info',
                            'labelTotal'    => '5',
                            'class'         => 'chat-panel',
                            'icon'          => 'icon-comments',
                            'subMenus'       => [
                                'class' => 'dropdown-alerts',
                                'menu'  => [
                                    [
                                        'href'  => '#',
                                        'desc'  => 'New Comment',
                                        'info'  => '4 minutes ago',
                                        'icon'  => 'icon-comment',
                                    ],
                                    [
                                        'href'  => '#',
                                        'desc'  => '3 New Follower',
                                        'info'  => '9 minutes ago',
                                        'icon'  => 'icon-twitter info',
                                    ],
                                    [
                                        'href'  => '#',
                                        'desc'  => 'Message Sent',
                                        'info'  => '20 minutes ago',
                                        'icon'  => 'icon-envelope',
                                    ],
                                    [
                                        'href'  => '#',
                                        'desc'  => 'New Task',
                                        'info'  => '1 Hour ago',
                                        'icon'  => 'icon-tasks',
                                    ],
                                    [
                                        'href'  => '#',
                                        'desc'  => 'Server Rebooted',
                                        'info'  => '2 Hour ago',
                                        'icon'  => 'icon-upload',
                                    ]
                                ]
                            ]
                        ],
                        [
                            'icon'          => 'icon-user',
                            'subMenus'       => [
                                'class' => 'dropdown-user',
                                'menu'  => [
                                    [
                                        'href'  => '#',
                                        'info'  => 'User Profile',
                                        'icon'  => 'icon-user',
                                    ],
                                    [
                                        'href'  => '#',
                                        'info'  => 'Settings',
                                        'icon'  => 'icon-gear',
                                    ],
                                    [
                                        'href'  => '/logout',
                                        'info'  => 'Logout',
                                        'icon'  => 'icon-signout',
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'userInfo'  => [
                        'href'      => '#',
                        'img'       => '/img/user.gif',
                        'name'      => 'Reinaldo K.',
                        'class'     => 'btn-success',
                        'status'    => 'Online'
                    ],
                    'menu'      => [
                        [
                            'href'  => '/luma',
                            'icon'  => 'icon-list-alt',
                            'text'  => 'LUMA',
                            'class'  => 'panel',
                            'menu'  => []
                        ],
                        [
                            'href'  => '#',
                            'icon'  => 'icon-tasks',
                            'text'  => 'UI Elements',
                            'total' => '0',
                            'class'  => 'panel',
                            'labelClass'  => 'label-default',
                            'menu'  => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Buttons',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Icons',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Progress',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Tabs & Panels',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Notification',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'More Notification',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Modals',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Wizard',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Sliders',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Typography',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'  => '#',
                            'icon'  => 'icon-pencil',
                            'text'  => 'Forms',
                            'class'  => 'panel',
                            'labelClass'  => 'label-success',
                            'total' => '0',
                            'menu'  => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'General',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Advance',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Validation',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'FileUpload',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'WYSIWYG / Editor',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'  => '#',
                            'icon'  => 'icon-table',
                            'text'  => 'Pages',
                            'class'  => 'panel',
                            'labelClass'  => 'label-info',
                            'total' => '0',
                            'menu'  => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Calendar',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Timeline',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Social',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Pricing',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Offline',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Under Construction',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'  => '#',
                            'icon'  => 'icon-bar-chart',
                            'text'  => 'Charts',
                            'class'  => 'panel',
                            'labelClass'  => 'label-danger',
                            'total' => '0',
                            'menu'  => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Line Charts',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Bar Charts',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Pie Charts',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Other Charts',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'  => '#',
                            'icon'  => 'icon-sitemap',
                            'text'  => '3 Level Menu',
                            'class'  => 'panel',
                            'total' => '0',
                            'menu'  => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-sitemap',
                                    'text'  => 'Demo Link 1',
                                    'class'  => '',
                                    'menu'  => [
                                        [
                                            'href'  => '#',
                                            'icon'  => 'icon-angle-right',
                                            'text'  => 'Demo Link 1',
                                            'class'  => '',
                                            'menu'  => []
                                        ],
                                        [
                                            'href'  => '#',
                                            'icon'  => 'icon-angle-right',
                                            'text'  => 'Demo Link 2',
                                            'class'  => '',
                                            'menu'  => []
                                        ],
                                        [
                                            'href'  => '#',
                                            'icon'  => 'icon-angle-right',
                                            'text'  => 'Demo Link 3',
                                            'class'  => '',
                                            'menu'  => []
                                        ]
                                    ]
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Demo Link 2',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Demo Link 3',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Demo Link 4',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'  => '#',
                            'icon'  => 'icon-folder-open-alt',
                            'text'  => '4 Level Menu',
                            'class'  => 'panel',
                            'total' => '0',
                            'menu'  => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-sitemap',
                                    'text'  => 'Demo Link 1',
                                    'class'  => '',
                                    'menu'  => [
                                        [
                                            'href'  => '#',
                                            'icon'  => 'icon-sitemap',
                                            'text'  => 'Demo Link 1',
                                            'class'  => '',
                                            'menu'  => [
                                                [
                                                    'href'  => '#',
                                                    'icon'  => 'icon-angle-right',
                                                    'text'  => 'Demo Link 1',
                                                    'class'  => '',
                                                    'menu'  => []
                                                ],
                                                [
                                                    'href'  => '#',
                                                    'icon'  => 'icon-angle-right',
                                                    'text'  => 'Demo Link 2',
                                                    'class'  => '',
                                                    'menu'  => []
                                                ]
                                            ]
                                        ],
                                        [
                                            'href'  => '#',
                                            'icon'  => 'icon-angle-right',
                                            'text'  => 'Demo Link 2',
                                            'class'  => '',
                                            'menu'  => []
                                        ],
                                        [
                                            'href'  => '#',
                                            'icon'  => 'icon-angle-right',
                                            'text'  => 'Demo Link 3',
                                            'class'  => '',
                                            'menu'  => []
                                        ]
                                    ]
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Demo Link 2',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Demo Link 3',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Demo Link 4',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'          => '#',
                            'icon'          => 'icon-warning-sign',
                            'text'          => 'Error Pages',
                            'class'         => 'panel',
                            'labelClass'    => 'label-warning',
                            'total'         => '0',
                            'menu'          => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Error 403',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Error 404',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Error 500',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Error 503',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'          => '#',
                            'icon'          => 'icon-film',
                            'text'          => 'Image Gallery',
                            'class'         => '',
                            'menu'          => []
                        ],
                        [
                            'href'          => '#',
                            'icon'          => 'icon-table',
                            'text'          => 'Data Tables',
                            'class'         => '',
                            'menu'          => []
                        ],
                        [
                            'href'          => '#',
                            'icon'          => 'icon-map-marker',
                            'text'          => 'Maps',
                            'class'         => '',
                            'menu'          => []
                        ],
                        [
                            'href'          => '#',
                            'icon'          => 'icon-columns',
                            'text'          => 'Grid',
                            'class'         => '',
                            'menu'          => []
                        ],
                        [
                            'href'  => '#',
                            'icon'  => 'icon-check-empty',
                            'text'  => 'Blank Pages',
                            'total' => '2',
                            'class'  => 'panel',
                            'labelClass'  => 'label-success',
                            'menu'  => [
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Blank Page One',
                                    'class'  => '',
                                    'menu'  => []
                                ],
                                [
                                    'href'  => '#',
                                    'icon'  => 'icon-angle-right',
                                    'text'  => 'Blank Page Two',
                                    'class'  => '',
                                    'menu'  => []
                                ]
                            ]
                        ],
                        [
                            'href'          => '#',
                            'icon'          => 'icon-signin',
                            'text'          => 'Login Page',
                            'class'         => '',
                            'menu'          => []
                        ]
                    ],
                    'content'   => [
                        'title' => 'Dashboard'
                    ],
                    'info'      => [
                        'list'          => [
                            [
                                'text'  => 'Visitor&nbsp;:',
                                'info'  => '23,000'
                            ],
                            [
                                'text'  => 'Users&nbsp;:',
                                'info'  => '53,000'
                            ],
                            [
                                'text'  => 'Registrations&nbsp;:',
                                'info'  => '3,000'
                            ]
                        ],
                        'buttons'       => [
                            [
                                'class' => 'btn-block',
                                'text'  => 'Help'
                            ],
                            [
                                'class' => 'btn-primary',
                                'text'  => 'Tickets'
                            ],
                            [
                                'class' => 'btn-info',
                                'text'  => 'New'
                            ],
                            [
                                'class' => 'btn-success',
                                'text'  => 'Users'
                            ],
                            [
                                'class' => 'btn-danger',
                                'text'  => 'Profit'
                            ],
                            [
                                'class' => 'btn-warning',
                                'text'  => 'Sales'
                            ],
                            [
                                'class' => 'btn-inverse',
                                'text'  => 'Stock'
                            ]
                        ],
                        'progress'   => [
                            [
                                'text'      => 'Profit',
                                'class'     => 'progress-bar-info',
                                'percent'   => '20'
                            ],
                            [
                                'text'      => 'Sales',
                                'class'     => 'progress-bar-success',
                                'percent'   => '40'
                            ],
                            [
                                'text'      => 'Pending',
                                'class'     => 'progress-bar-warning',
                                'percent'   => '60'
                            ],
                            [
                                'text'      => 'Summary',
                                'class'     => 'progress-bar-danger',
                                'percent'   => '80'
                            ]
                        ]
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

