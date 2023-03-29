<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Infrastructure\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


final class ArticleController extends AbstractController
{

	#[Route('/', name: 'index', methods: ['GET'])]
	public function index()
	{
		return $this->render('base.html.twig', []);
	}

	#[Route('/page1', name: 'page1', methods: ['GET'])]
	public function page1()
	{
		return $this->render('page1.html.twig', []);
	}

	#[Route('/page2', name: 'page2', methods: ['GET'])]
	public function page2()
	{
		return $this->render('page2.html.twig', []);
	}
}
