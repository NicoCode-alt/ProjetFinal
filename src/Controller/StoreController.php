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
     * @Route("/store/", name="store")
     */
    public function index(SpotifyWebAPI $api): Response
    {
        $offset = 0;
        $id = 0;
        $search = $api->search('A','album',[
            'offset' => $offset,
            'limit' => 20
            ]);

        $albums = $search->albums->items;

        $newReleases = $api->getNewReleases(['limit'=>50]);
        
        return $this->render('store/index.html.twig', [
            'albums' => $albums,
            'newReleases' => $newReleases->albums->items,
            'id' => $id+1
        ]);
    
    }

    /**
     * @Route("/store/album/{id}", name="view_album")
     */
    public function show(SpotifyWebAPI $api, Request $request, $id): Response
    {
        $album = $api->getAlbum($id);

        return $this->render('store/view_album.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @Route("/store/search/{id}", name="search")
     */
    public function searchInc(SpotifyWebAPI $api, Request $request, $id): Response
    {
        $search = $request->get('search') ?? 'A';

        $offset = $id*20;
        $search = $api->search("album:$search",'album',[
            'offset' => $offset,
            'limit' => 20
        ]);

        $albums = $search->albums->items;

        return $this->render('store/search.html.twig', [
            'albums' => $albums,
            'id' => $id+1
        ]);
    }
    /**
     * @Route("/categories/", name="categories")
     */
    public function showCategories(SpotifyWebAPI $api): Response
    {
        $searchCategories = $api->getCategoriesList(['limit'=>50]);
        
        $categories = $searchCategories->categories->items;
       
        return $this->render('store/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categories/{id}", name="view_category")
     */
    public function showCategory(SpotifyWebAPI $api, $id): Response
    {
        $offset = 0;
        $searchCategory = $api->search('A','category',[
            'offset' => $offset,
            'limit' => 20
            ]);
        $category = $searchCategory->categories->items;
       
        return $this->render('store/category.html.twig', [
            'category' => $category
        ]);
    }


    
}