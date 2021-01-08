<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Flow\CSV\Builder;

use PhpParser\Builder;
use PhpParser\Node;

final class Loader implements Builder
{
    private ?Node\Expr $logger;

    public function __construct(
        private Node\Expr $filePath,
        private Node\Expr $delimiter,
        private Node\Expr $enclosure,
        private Node\Expr $escape,
    )
    {
    }

    public function withLogger(Node\Expr $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getNode(): Node
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified('Kiboko\\Component\\ETL\\Flow\\SPL\\CSV\\Safe\\Loader'),
            args: [
                new Node\Expr\New_(
                    class: new Node\Name\FullyQualified('SplFileObject'),
                    args: [
                        new Node\Arg($this->filePath),
                        new Node\Arg(new Node\Scalar\String_('w')),
                    ]
                ),
                new Node\Arg($this->delimiter),
                new Node\Arg($this->enclosure),
                new Node\Arg($this->escape),
            ]
        );
    }
}
