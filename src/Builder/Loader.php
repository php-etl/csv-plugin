<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Flow\CSV\Builder;

use PhpParser\Builder;
use PhpParser\Node;

final class Loader implements Builder
{
    private ?Node\Expr $logger;

    public function withLogger(Node\Expr $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getNode(): Node
    {
        return new Node\Expr\New_(class: new Node\Name\FullyQualified('Kiboko\\Component\\Flow\\Csv\\Safe\\Loader'));
    }
}
