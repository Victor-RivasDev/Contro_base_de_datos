<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Entity\Post;
use App\Entity\Comment;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(PostRepository $post): Response
    {
        return $this->render('page/home.html.twig', [
            'posts' => $post->findLatest(),
        ]);
    }
    
    #[Route('/blog/{slug}', name: 'app_post')]
    public function post(#[MapEntity(mapping: ['slug' => 'slug'])] Post $post): Response
    {   
        // Creamos una instancia vacía del comentario para pintar el formulario en la vista del post
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('page/post.html.twig', [
            'post' => $post, 
            'form' => $form->createView()
        ]);
    }

    #[Route('/nuevo-comentario/{slug}', name: 'app_comment_new')] // Cambiado a 'app_comment_new' para que coincida con tu Twig
    public function comment(
        Request $request, 
        #[MapEntity(mapping: ['slug' => 'slug'])] Post $post, 
        EntityManagerInterface $entityManager
    ): Response { 
        $comment = new Comment();
        $comment->setPost($post); // Asociamos el comentario al post actual

        // Si tienes un usuario conectado, lo asociamos también
        if ($this->getUser()) {
            $comment->setUser($this->getUser());
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment); // Ahora sí existe $comment
            $entityManager->flush();

            return $this->redirectToRoute('app_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('page/post.html.twig', [
            'post' => $post, 
            'form' => $form->createView()
        ]);
    }
}