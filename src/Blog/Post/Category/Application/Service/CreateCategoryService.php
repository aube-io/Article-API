<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Service;

use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Entity\AuthorId;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateCategoryService
{
    private CategoryRepositoryInterface $categoryRepository;
    private EventDispatcherInterface $eventDispatcher;
    private SerializerInterface $serializer;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    public function handle(string $name, string $slug): string
    {
		$category = Category::create(
            new CategoryId(Uuid::uuid4()->toString()),
            $name,
            $slug
        );

        $this->categoryRepository->save($category);

        foreach ($category->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->serializer->serialize($category, 'json');
    }
}