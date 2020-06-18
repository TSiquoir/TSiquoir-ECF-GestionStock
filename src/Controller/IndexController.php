<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Brand;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request)
    {
        // creates a task object and initializes some data for this example
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('reference', TextType::class)
            ->add('name', TextType::class)
            ->add('brand', EntityType::class, [
                // looks for choices from this entity
                'class' => Brand::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            ])
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
                'multiple' => true,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            ])
            ->add('quantity', NumberType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();

            
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}