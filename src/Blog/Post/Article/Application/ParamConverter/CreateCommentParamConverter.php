<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\ParamConverter;

use App\Blog\Post\Article\Application\Model\CreateCommentCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

final class CreateCommentParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $createCommentCommand = new CreateCommentCommand();
        $createCommentCommand->setArticleId($parameters['article_id']);
        $createCommentCommand->setEmail($parameters['email']);
        $createCommentCommand->setMessage($parameters['message']);

        $request->attributes->set($configuration->getName(), $createCommentCommand);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return 'createCommentCommand' === $configuration->getName();
    }
}