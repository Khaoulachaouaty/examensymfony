<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class IndexController extends AbstractController
{
    ///affiche tout les articles
    #[Route('/', name: 'home.index')]
    public function home(ArticleRepository $repository): Response
    {
        //return new Response('<h1>Ma première page Symfony</h1>');
        //return $this->render('article/index.html.twig');
        /*** */
        //$articles = ['Article 1', 'Article 2', 'Article 3'];
        //return $this->render('articles/index.html.twig', ['articles' => $articles]);
        /***** */
        //récupérer tous les articles de la table article de la BD
        // et les mettre dans le tableau $articles
        #$articles= $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig',['articles'=>$repository-> findAll()]);
    }

    /*#[Route('/article/save', name: 'article.save')]
    public function save(EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(1000);
        $entityManager->persist($article);
        $entityManager->flush();
        return new Response('Article enregistré avec l\'identifiant ' . $article->getId());
    }*/

    
    /*ajout article*/
    #[Route('/article/nouveau', name: 'article.new', methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) {
        $article = new Article();
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('home.index');
        }

        return $this->render('articles/new.html.twig',['form' => $form->createView()]);
    }

        //afficher détail d'article
        #[Route('/article/{id}', name:'article_show')]
        public function show($id,EntityManagerInterface $entityManager) {
            $article = $entityManager->getRepository(Article::class)->find($id);
            return $this->render('articles/show.html.twig',
            array('article' => $article));
        }


    //modifier article
    #[Route('/article/edit/{id}', name:'edit_article', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id,EntityManagerInterface $entityManager) {
    $article = new Article();
    $article = $entityManager->getRepository(Article::class)->find($id);
    
    $form = $this->createFormBuilder($article)
    ->add('nom', TextType::class)
    ->add('prix', TextType::class)
    ->add('save', SubmitType::class, array(
    'label' => 'Modifier' 
    ))->getForm();
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $entityManager->flush();
    
    return $this->redirectToRoute('home.index');
    }
    return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }


    //supprimer article
    #[Route('/article/delete/{id}', name:'delete_article', methods: ['GET','DELETE'])]
    public function delete(Request $request, $id,EntityManagerInterface $entityManager) {
        $article = $entityManager->getRepository(Article::class)->find($id);
        $entityManager->remove($article);
        $entityManager->flush();
        
        $response = new Response();
        $response->send();
    return $this->redirectToRoute('home.index');
    }
   
    //save article
    /*#[Route('/article/save')]
    public function save(EntityManagerInterface $entityManager):Response
     {
   
    $article = new Article();
    $article->setNom('Article 3');
    $article->setPrix(3000);
    $entityManager->persist($article);
    $entityManager->flush();
    return new Response('Article enregisté avec id '.$article->getId());
    }*/
   

}