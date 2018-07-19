<?php
namespace App\Service\SSO;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AuthExceptions
{
    
    private $authExceptions;
    
    public function __construct(ParameterBagInterface $objParameterBagInterface)
    {
        $this->authExceptions = ($objParameterBagInterface->has('auth_exceptions')?$objParameterBagInterface->get('auth_exceptions'):[]);
    }
    public function allowUnauthorizedAccess($ip, $route)
    {
        $routes = $this->getIpRoutes($ip);
        if ($routes) {
            if (is_array($routes)) {
                return in_array($route, $routes);
            } else {
                return (($routes == '*') || ($routes == $route));
            }
        }
        return false;
    }
    
    public function getIpRoutes($ip)
    {
        foreach ($this->authExceptions as $sytem) {
            if (in_array($ip, $sytem['ips'])) {
                return $sytem['routes'];
            }
        }
        return false;
    }}

