<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Builder;

use PhpParser\Builder;
use PhpParser\Node;

final class Extractor implements Builder
{
    private ?Node\Expr $logger;

    public function __construct(
        private ?Node\Expr $filePath,
        private ?Node\Expr $delimiter = null,
        private ?Node\Expr $enclosure = null,
        private ?Node\Expr $escape = null,
    ) {
        $this->logger = null;
    }

    public function withFilePath(Node\Expr $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function withDelimiter(Node\Expr $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function withEnclosure(Node\Expr $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    public function withEscape(Node\Expr $escape): self
    {
        $this->escape = $escape;

        return $this;
    }

    public function withLogger(Node\Expr $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getNode(): Node
    {
        $arguments = [
            new Node\Arg(
                value: new Node\Expr\New_(
                    class: new Node\Name\FullyQualified('SplFileObject'),
                    args: [
                        new Node\Arg($this->filePath),
                        new Node\Arg(new Node\Scalar\String_('r')),
                    ],
                ),
                name: new Node\Identifier('file'),
            ),
        ];

        if ($this->delimiter !== null) {
            array_push(
                $arguments,
                new Node\Arg(
                    value: $this->delimiter,
                    name: new Node\Identifier('delimiter'),
                ),
            );
        }

        if ($this->enclosure !== null) {
            array_push(
                $arguments,
                new Node\Arg(
                    value: $this->delimiter,
                    name: new Node\Identifier('enclosure'),
                ),
            );
        }

        if ($this->escape !== null) {
            array_push(
                $arguments,
                new Node\Arg(
                    value: $this->delimiter,
                    name: new Node\Identifier('escape'),
                ),
            );
        }

        $instance = new Node\Expr\New_(
            class: new Node\Name\FullyQualified('Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor'),
            args: $arguments,
        );

        if ($this->logger !== null) {
            return new Node\Expr\MethodCall(
                var: $instance,
                name: 'setLogger',
                args: [
                    new Node\Arg($this->logger),
                ]
            );
        }

        return $instance;
    }
}
