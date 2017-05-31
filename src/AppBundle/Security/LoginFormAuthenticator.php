<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Security;

use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Form\Type\LoginForm;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;


/**
 * Description of FormLoginAuthenticator
 *
 * @author Nick
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
    * @var \Symfony\Component\Routing\RouterInterface
    */
    private $router;
    private $em;
    private $formFactory;
    private $passwordEncoder;
    
    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
        if (!$isLoginSubmit) {
            // skip authentication
            return;
        }
        
        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);
        $data = $form->getData();
        
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );
        
        return $data;
    }
    
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];
        return $userProvider->loadUserByUsername($username);
    }
    
    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }
        return false;
    }
    
    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }
    
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->container->router->generate('/');
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }

}
