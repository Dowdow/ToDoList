<?php

namespace LeoAndLeo\GoogleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

class GoogleController extends Controller
{
    public function callbackAction()
    {
        $request = $this->get('request');

        if($request->query->get('error')){
            return $this->render('LeoAndLeoGoogleBundle::callback.html.twig');
        }

        if($request->query->get('code')) {
            $client = $this->get('happyr.google.api.client');

            try {
                $client->authenticate($request->query->get('code'));
            } catch(\Google_Auth_Exception $e) {
                return $this->render('LeoAndLeoGoogleBundle::callback.html.twig');
            }

            $accessToken = $client->getAccessToken();
            $accessToken = json_decode($accessToken);
            $accessToken = $accessToken->access_token;

            $this->securityContext =$this->get('security.context');

            $token = $this->securityContext->getToken();
            $token = new PreAuthenticatedToken(
                $accessToken,
                $token->getCredentials(),
                $token->getProviderKey(),
                [ 'ROLE_HAS_TOKEN' ]
            );
            $this->securityContext->setToken($token);

            var_dump($token);
        }

        return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.list_index'));
    }
}