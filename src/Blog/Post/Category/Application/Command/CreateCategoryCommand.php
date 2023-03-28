<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Command;

use App\Blog\Post\Category\Application\Service\CreateCategoryService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-category',
    description: 'Creates a new category.',
    aliases: ['app:add-category'],
    hidden: false
)]
final class CreateCategoryCommand extends Command
{
    private CreateCategoryService $createCategoryService;

    public function __construct(CreateCategoryService $createCategoryService)
    {
        $this->createCategoryService = $createCategoryService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create category...')
                ->addArgument('name', InputArgument::REQUIRED, 'Category name')
                ->addArgument('slug', InputArgument::REQUIRED, 'Category slug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $category = $this->createCategoryService->handle(
            $input->getArgument('name'),
            $input->getArgument('slug')
        );

        $output->writeln([
            'Category Created : ',
        ]);

        $output->write($category);

        return Command::SUCCESS;
    }
}