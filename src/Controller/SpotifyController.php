<?php

// src/Controller/SomeController.php

namespace App\Controller;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIAuthException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpotifyController extends AbstractController
{
    private $api;
    private $session;

    public function __construct(SpotifyWebAPI $api, Session $session)
    {
        $this->api = $api;
        $this->session = $session;
    }

    /**
     * @Route("/callback")
     */
    public function callbackFromSpotify(Request $request): Response
    {
        try {
            $this->session->requestAccessToken($request->query->get('code'));
        } catch (SpotifyWebAPIAuthException $e) {
            return $this->redirectToRoute('some_redirect');
        }

        $this->api->setAccessToken($this->session->getAccessToken());
        $me = $this->api->me();

        return new Response(var_export($me, false), 200, ['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("/redirect", name="some_redirect")
     */
    public function redirectToSpotify(): Response
    {
        $options = [
            'scope' => [
                'user-read-email',
            ],
        ];

        return $this->redirect($this->session->getAuthorizeUrl($options));
    }
}
