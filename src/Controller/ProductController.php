<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Form\CartType;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}")
 */

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();

        $products = $pdo->getRepository(Product::class)->findAll();

        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $file = $form->get('pictureUpload')->getData();
            if ($file){
                $fileName = uniqid('', true) . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('upload_dir'),
                        $fileName
                    );
                } catch (FileException $e){
                    $this->addFlash('danger', 'Impossible');
                    return $this->redirectToRoute('/');
                }
                $product->setPicture($fileName);
            }
            $pdo->persist($product);
            $pdo->flush();
        }

        return $this->render('product/index.html.twig',[
            'products' => $products,
            'add_form' => $form->createView()
        ]);
    }
    /**
     * @Route("/product/{id}", name="a_product")
     * */
    public function product(Product $product=null,Cart $cart=null, Request $request, TranslatorInterface $translator)
    {
        if ($cart == null){
            $cart = new Cart();
        }
        if ($cart != null) {
            $form = $this->createForm(CartType::class, $cart);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pdo = $this->getDoctrine()->getManager();
                $product->setCart($cart);
                $pdo->persist($cart);
                $pdo->flush();
                $this->addFlash('success', $translator->trans('product.added'));

            }

            return $this->render('product/product_single.html.twig', [
                'product' => $product,
                'form_add_cart' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('products');
            $this->addFlash('error', $translator->trans('product.NotFound'));

        }
    }
    /**
     * @Route("/product/delete/{id}", name="delete_product")
     * */
    public function delete(Product $product=null, TranslatorInterface $translator){

        if ($product != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($product);
            $pdo->flush();
            $this->addFlash('success', $translator->trans('product.Deleted'));
        } else{
            $this->addFlash('error',$translator->trans('product.NotFound'));
        }
        return $this->redirectToRoute('products');
    }
}
