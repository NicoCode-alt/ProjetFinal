<?php

namespace App\Manager;
use App\Entity\Cart;
use App\Entity\User;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;

Class CartManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StripeService $stripeService
    ){
     $this->em = $entityManager;
     $this->stripeService = $stripeService;
    }

    public function getCarts()
    {
        return $this->em->getRepository(Cart::class)
            ->findAll();
    }

    public function intentSecret(Cart $cart)
    {
        $intent = $this->stripeService->paymentIntent($cart);

        return $intent['client_secret'] ?? null;
    }

    /**
    * @param array $stripeParameter
    * @param Cart $cart
    * @return array|null
    */
    public function stripe(array $stripeParameter, Cart $cart)
    {
        $resource = null;
        $data = $this->stripeService->stripe($stripeParameter, $cart);

        if($data){
            $resource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['status'],
                'stripeToken' => $data['client_secret']
            ];
        }
        return $resource;
    }

    /**
    * @Route("cart/subscription/", name="subscription")
    * @param array $resource
    * @param Cart $cart
    * @param User $user
    */
    public function create_subscription(array $resource, Cart $cart, User $user, EntityManagerInterface $manager )
    {

        // $cart = new Cart();
        //     $cart->setUser($user)
        //         ->setAlbumName($album->name)
        //         ->setArtistName($album->artists[0]->name)
        //         ->setCreatedAt($createdAt)
        //         ->setPrice($price)
        //         ->setProductId($id)
        //         ->setQuantity($quantity)
        //         ->setOrderId($orderId);
                     
        //         $manager->persist($cart);
        
        // $order = new Order();
        // $order->setUser($user->getLastname())
        //     ->setProduct($cart)
        //     ->setPrice($cart)
        //     ->setReference(uniqid('', false))
        //     ->setBrandStripe($resource['stripeBrand'])
        //     ->setLast4Stripe($resource['stripeLast4'])
        //     ->setBrandIdChargeStripe($resource['stripeBrand'])
        //     ->setBrandIdStripeToken($resource['stripeToken'])
        //     ->setBrandStatusStripe($resource['stripeStatus'])
        //     ->setUpdatedAt(new \DateTime())
        //     ->setCreatedAt(new \DateTime());
        
        // $manager->persist($order);
    }
}