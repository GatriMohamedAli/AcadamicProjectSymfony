<?php

namespace App\OauthProviders;

use App\EventListener\GithubException;
use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Laminas\Code\Generator\Exception\ClassNotFoundException;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function PHPUnit\Framework\throwException;

class GithubAuthenticator extends SocialAuthenticator
{

    private $router;

    private $clientRegistry;

    private $userRepository;

    public function __construct(RouterInterface $router,ClientRegistry $clientRegistry, UserRepository $userRepository){
        $this->router=$router;
        $this->clientRegistry = $clientRegistry;
        $this->userRepository = $userRepository;
    }
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate("login"));
    }

    public function supports(Request $request)
    {
        return 'oauth_check' === $request->attributes->get('_route') && $request->get('service')==='github';
        // TODO: Implement supports() method.
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->clientRegistry->getClient('github'));
        // TODO: Implement getCredentials() method.
    }

    /**
     * @param AccessToken $credentials
     * @throws GithubException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GithubResourceOwner $client */
        $client=$this->clientRegistry->getClient('github')->fetchUserFromToken($credentials);
        dd($client);
        $githubUser=$this->getEmailFromGithubApi($credentials,$client);
        if ($githubUser->getEmail() === null){
            throw new GithubException();
        }
        $user=$this->userRepository->findOrCreateByGithubAuth($githubUser);
        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('userFront'));
    }

    public function getEmailFromGithubApi($credentials, $client){
        $response=HttpClient::create()->request(
            'GET',
            'https://api.github.com/user/emails',
            [
                'headers'=>[
                    'authorization'=>"token {$credentials->getToken()}"
                ]
            ]
        );
        $emails= json_decode($response->getContent(),true);
        foreach ($emails as $email){
            if ($email['primary']===true && $email['verified']===true){
                $data=$client->toArray();
                $data['email']=$email['email'];
                $client=new GithubResourceOwner($data);
            }
        }
        return $client;

    }
}