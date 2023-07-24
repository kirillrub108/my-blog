<?php

declare(strict_types=1);

namespace Blog\Twig;

use Twig\TwigFunction;

class TwigFunctionFactory
{
    public function create(...$arguments): TwigFunction
    {
        return new TwigFunction(...$arguments);
    }
}