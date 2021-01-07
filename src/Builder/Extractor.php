<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Flow\CSV\Builder;

use PhpParser\Builder;
use PhpParser\Node;
use Kiboko\Component\ETL\Flow\SPL\CSV\Safe\Loader as SafeLoader;
use Kiboko\Component\Flow\Csv\Safe\Extractor as SafeExtractor;

final class Extractor implements Builder
{
    private ?Node\Expr $logger;

    public function withLogger(Node\Expr $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getNode(): Node
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(SafeExtractor::class)
        );
    }
}
