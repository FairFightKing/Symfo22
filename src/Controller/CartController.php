<?php

namespace App\Controller;

use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart")
     */
    public function index(Request $request)
    {
        try{
            $pdo = $this->getDoctrine()->getManager();

            $cart = $pdo->getRepository(Cart::class)->findAll();
    
            return $this->render('cart/index.html.twig',[
                'cart' => $cart,
            ]);
        }catch (Exception $e){
            echo "erreur";
            echo $e->getMessage();

        }
       
    }
    /**
     * @Route("/cart/delete/{id}", name="delete_cart")
     * */
    public function delete(Cart $cart=null, TranslatorInterface $translator){

        if ($cart != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($cart);
            $pdo->flush();
            $this->addFlash('success', $translator->trans('cart.Deleted'));
        } else{
            $this->addFlash('error',$translator->trans('cart.NotFound'));
        }
        return $this->redirectToRoute('cart');
    }
}
