<?php

namespace App\Security;

use App\Document\ApiToken;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private const TOKEN_HEADER = 'x-api-token';

    public function __construct(private readonly DocumentManager $documentManager) { }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::TOKEN_HEADER);
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get(self::TOKEN_HEADER);

        if (!$token)
            throw new CustomUserMessageAuthenticationException('No API token provided');

        return new SelfValidatingPassport(
            new UserBadge($token, function($token) {
                $apiToken = $this
                    ->documentManager
                    ->getRepository(ApiToken::class)
                    ->findOneBy([
                        'token' => $token
                    ]);

                if (!$apiToken)
                    throw new UserNotFoundException();

                return $apiToken;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
