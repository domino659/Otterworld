<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Service\MarkdownHelper;

class MarkdownExtension extends AbstractExtension
{
    private $helper;

    public function __construct(MarkdownHelper $helper)
    {
        $this->helper = $helper;
    }
     
    public function getFilters(): array
    {
        return [
            new TwigFilter('markdown_cached', [$this, 'cacheMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    public function cacheMarkdown($value)
    {
        return $this->helper->parse($value);
    }
}
