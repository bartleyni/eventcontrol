<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Security;

use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
/**
 * Description of FormLoginAuthenticator
 *
 * @author Nick
 */
class FormLoginAuthenticator extends AbstractGuardAuthenticator
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login_check') {
            return;
        }
        $username = $request->request->get('_username');
        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $password = $request->request->get('_password');
        return array(
            'username' => $username,
            'password' => $password
        );
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];
        return $userProvider->loadUserByUsername($username);
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];
        $encoder = $this->container->get('security.password_encoder');
        if (!$encoder->isPasswordValid($user, $plainPassword)) {
            // throw any AuthenticationException
            throw new BadCredentialsException();
        }
    }
    protected function getLoginUrl()
    {
        return $this->container->get('router')
            ->generate('login');
    }
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->container->get('router')
            ->generate('/');
    }
    
  /**
   * {@inheritdoc}
   */
  public function start(Request $request, AuthenticationException $authException = null)
  {
    $url = $this->router->generate('login');
    return new RedirectResponse($url);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsRememberMe()
  {
    return false;
  }
}


 