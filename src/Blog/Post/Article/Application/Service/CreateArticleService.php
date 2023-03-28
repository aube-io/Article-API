<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Entity\ArticleId;
use App\Blog\Post\Article\Domain\Entity\AuthorId;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use App\Blog\User\Infrastructure\Repository\UserRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateArticleService
{
    private ArticleRepositoryInterface $articleRepository;
    private EventDispatcherInterface $eventDispatcher;
    private SerializerInterface $serializer;

	private UserRepositoryInterface $userRepository;
	private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        UserRepositoryInterface $userRepository,
        CategoryRepositoryInterface $categoryRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->articleRepository = $articleRepository;
		$this->userRepository = $userRepository;
		$this->categoryRepository = $categoryRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    public function handle(string $title, string $body, string $authorEmail, string $categorySlug): string
    {

		$user = $this->userRepository->findOneBy(["email" => $authorEmail]);
        if (!$user) {
            throw new \InvalidArgumentException('author not found');
        }

		$category = $this->categoryRepository->findOneBy(["slug" => $categorySlug]);
        if (!$category) {
            throw new \InvalidArgumentException('author not found');
        }

		$article = Article::create(
            new ArticleId(Uuid::uuid4()->toString()),
            $title,
            $body,
            new AuthorId($user->getId()),
            new CategoryId($category->getId())
        );

        $this->articleRepository->save($article);

        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->serializer->serialize($article, 'json');
    }
}