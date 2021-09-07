<?php

namespace App\Controller;

use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use SpotifyWebAPI\SpotifyWebAPI;
// use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="view_cart")
     */
    public function index(SessionInterface $session, SpotifyWebAPI $api): Response
    {
        $cart = $session->get('cart');

        $cartData = [];

        foreach($cart as $id=>$quantity){
            $cartData[]=[
                'product'=> $api->getAlbum($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach($cartData as $item){
            $totalItem = $item['quantity'] * 5.99;
            $total += $totalItem;
        }

        return $this->render('cart/view_cart.html.twig', [
            'items' => $cartData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
        public function add($id, SessionInterface $session): Response
        {


            $cart = $session->get('cart', []);

            if(!empty($cart[$id])){
                $cart[$id]++;
            } else {
                $cart[$id] = 1;
            }

            $session->set('cart', $cart);

            return $this->redirectToRoute('view_cart');
        }

        /**
         * @Route("/cart/remove/{id}", name="cart_remove")
         */
        public function remove($id, SessionInterface $session): Response
        {

            $cart = $session->get('cart',[]);

            if(!empty($cart[$id]))
            {
                unset($cart[$id]);
            }

            $session->set('cart', $cart);

            return $this->redirectToRoute('view_cart');
        }

        /**
         * @Route("/cart/pay", name="pay_cart", priority=1)
         */
        public function payCart(SpotifyWebAPI $api, SessionInterface $session, EntityManagerInterface $manager): Response
        {
            
            $cartData = $session->get('cart', []);

            $price = 5.99;
            $createdAt = new \DateTime();
            $user = $this->getUser();
            $orderId= $createdAt->format('ymdHi').random_int(1000,10000);
            $finalPrice = 0;

            foreach ($cartData as $id=>$quantity)
            {
                $album = $api->getAlbum($id);
                
                $cartPay[]= [
                   'albumId' => $id,
                   'albumName' => $album->name,
                   'artistName' => $album->artists[0]->name,
                   'albumPrice' => $price,
                   'albumQuantity' => $quantity,
                   'imageUrl' => $album->images[0]->url
                ];

                $finalPrice = $finalPrice + $price * $quantity;

                $cart = new Cart();
                $cart->setUser($user)
                    ->setAlbumName($album->name)
                    ->setArtistName($album->artists[0]->name)
                    ->setCreatedAt($createdAt)
                    ->setPrice($price)
                    ->setProductId($id)
                    ->setQuantity($quantity)
                    ->setOrderId($orderId);
                $manager->persist($cart);
            }

            // $stripe = json_decode(file_get_contents(filename:'php://input.php'));

            // header(header:'ContentType: application/jason');
            // echo json_encode($stripe);


            $manager->flush();

            
            return $this->render('cart/pay_cart.html.twig',[
                'items' => $cartPay,
                'finalPrice' => $finalPrice,
                'orderId' => $orderId
            ]);
        }
}
