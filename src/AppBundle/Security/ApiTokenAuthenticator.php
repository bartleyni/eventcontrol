<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Security;

use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

/**
 * Description of ApiTokenAuthenticator
 *
 * @author Nick
 */
class ApiTokenAuthenticator extends AbstractGuardAuthenticator 
{
    private $em;
    private $router;
    
    public function __construct(EntityManager $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }
    
    public function getCredentials(Request $request)
    {
        if(!$token = $request->headers->get('X-AUTH-TOKEN'))
        {
            $token = null;
        }
        return array(
            'token' => $token,
        );
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $this->em->getRepository('AppBundle:User')
            ->findOneBy(array('apiToken' => $credentials));
        // we could just return null, but this allows us to control the message a bit more
        if (!$user) {
            throw new AuthenticationCredentialsNotFoundException();
        }
        
//        $apiKey = $credentials['token'];
//
//        if (null === $apiKey) {
//            return;
//        }

        // if a User object, checkCredentials() is called
        //return $userProvider->loadUserByUsername($apiKey);
        return $user;
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        // the fact that they had a valid token that *was* attached to a user
        // means that their credentials are correct. So, there's nothing
        // additional (like a password) to check here.
        return;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(
            // you could translate the message
            array('message' => $exception->getMessageKey()),
            403
        );
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // do nothing - let the request just continue!
        return;
    }
    public function supportsRememberMe()
    {
        return false;
    }
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(
            // you could translate the message
            array('message' => 'Authentication required'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
