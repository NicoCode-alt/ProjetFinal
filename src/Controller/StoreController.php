<?php

namespace App\Controller;

use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreController extends AbstractController
{
    /**
     * @Route("/store", name="store")
     */
    public function index(SpotifyWebAPI $api): Response
    {
        
        $offset = 0;
        
                        
        $search = $api->search('A','album',[
            'offset' => $offset,
            'limit' => 30
            ]);
           $test= $api->getAlbum('41GuZcammIkupMPKH2OJ6I');

        $albums = $search->albums->items;

        $newReleases = $api->getNewReleases();
        
        
        
        return $this->render('store/index.html.twig', [
            'albums' => $albums,
            'newReleases' => $newReleases->albums->items
        ]);
    }
}
