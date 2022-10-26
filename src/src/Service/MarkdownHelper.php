<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class MarkdownHelper
{
  private $markdownParser;
  private $cache;
  private $isDebug;
  private $logger;
  private Security $security;

  public function __construct(MarkdownParserInterface $markdownParser,
                              CacheInterface $cache,
                              bool $isDebug,
                              LoggerInterface $mdLogger,
                              Security $security)
  {
    $this->markdownParser = $markdownParser;
    $this->cache = $cache;
    $this->isDebug = $isDebug;
    $this->logger = $mdLogger;
    $this->security = $security;
  }
  
  public function parse(string $source): string
  {
    if ($this->isDebug) {
      $this->logger->info('Je ne suis pas en cache');
    
      if ($this->security->getUser()) {
        $this->logger->info($this->security->getUser()->getUsername());
      }

      return $this->markdownParser->transformMarkdown($source);
    }
  }
}