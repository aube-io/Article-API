<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Command;

use App\Blog\Post\Article\Application\Service\CreateArticleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-article',
    description: 'Creates a new article.',
    aliases: ['app:add-article'],
    hidden: false
)]
final class CreateArticleCommand extends Command
{
    private CreateArticleService $createArticleService;

    public function __construct(CreateArticleService $createArticleService)
    {
        $this->createArticleService = $createArticleService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create article...')
                ->addArgument('title', InputArgument::REQUIRED, 'Article title')
                ->addArgument('body', InputArgument::REQUIRED, 'Article body')
                ->addArgument('author-email', InputArgument::REQUIRED, 'Article author mail')
                ->addArgument('category-slug', InputArgument::REQUIRED, 'Article category name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $article = $this->createArticleService->handle(
            $input->getArgument('title'),
            $input->getArgument('body'),
            $input->getArgument('author-email'),
            $input->getArgument('category-slug')
        );

        $output->write($article);

        return Command::SUCCESS;
    }
}