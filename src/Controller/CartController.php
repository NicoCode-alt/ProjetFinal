<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Cart;
use App\Manager\CartManager;
use App\Services\StripeService;
use SpotifyWebAPI\SpotifyWebAPI;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        public function payCart(SpotifyWebAPI $api, SessionInterface $session, EntityManagerInterface $manager, CartManager $cartManager): Response
        {
            if(!$this->getUser()){
                return $this->redirectToRoute('login');
            }
            
            $cartData = $session->get('cart', []);

            $price = 5.99;
            $createdAt = new \DateTime();
            $user = $this->getUser();
            $orderId= $createdAt->format('ymd').uniqid();
            $finalPrice = 0;

            foreach ($cartData as $id => $quantity)
            {
                $album = $api->getAlbum($id);
                
                $cartPay[]= [
                   'orderId' => $orderId,
                   'albumId' => $id,
                   'albumName' => $album->name,
                   'artistName' => $album->artists[0]->name,
                   'albumPrice' => $price,
                   'albumQuantity' => $quantity,
                   'imageUrl' => $album->images[0]->url
                ];
                $finalPrice = $finalPrice + $price * $quantity;
            }
            return $this->render('cart/pay_cart.html.twig',[
                'items' => $cartPay,
                'user' => $this->getUser(),
                // 'intentSecret' => $cartManager->intentSecret($cartPay),
                'finalPrice' => $finalPrice,
                'orderId' => $orderId
            ]);
        }

        public function IntentSecret(Cart $cart)
        {
            $intent = $this->stripeService->paymentIntent($cart);

            return $intent['client_secret'] ?? null;
        }

        /**
         * @Route("/cart/subscription/{id}/paiement/load", name"paiement_load")
         */
        public function subscription(Cart $cart, Request $request, CartManager $cartManager)
        {
            $user = $this->getUser();

            if($request->getMethod() === "POST")
            {
                $resource = $cartManager->stripe($_POST, $cart);

                if(null!== $resource)
                {
                    // $cartManager->create_subscription($resource, $cart, $user);

                    return $this->redirectToRoute('store');
                }
            }
            return $this->redirectToRoute('pay_cart');
        }
}
