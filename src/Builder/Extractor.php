<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Builder;

use Kiboko\Contract\Configurator\StepBuilderInterface;
use PhpParser\Node;

final class Extractor implements StepBuilderInterface
{
    private ?Node\Expr $logger;
    private ?Node\Expr $rejection;
    private ?Node\Expr $state;

    public function __construct(
        private ?Node\Expr $filePath,
        private ?Node\Expr $delimiter = null,
        private ?Node\Expr $enclosure = null,
        private ?Node\Expr $escape = null,
        private ?Node\Expr $columns = null,
        private bool $safeMode = true,
    ) {
        $this->logger = null;
        $this->rejection = null;
        $this->state = null;
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

    public function withColumns(Node\Expr $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function withLogger(Node\Expr $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function withRejection(Node\Expr $rejection): self
    {
        $this->rejection = $rejection;

        return $this;
    }

    public function withState(Node\Expr $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function withSafeMode(): self
    {
        $this->safeMode = true;

        return $this;
    }

    public function withFingersCrossedMode(): self
    {
        $this->safeMode = false;

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
                    value: $this->enclosure,
                    name: new Node\Identifier('enclosure'),
                ),
            );
        }

        if ($this->escape !== null) {
            array_push(
                $arguments,
                new Node\Arg(
                    value: $this->escape,
                    name: new Node\Identifier('escape'),
                ),
            );
        }

        if ($this->columns !== null) {
            array_push(
                $arguments,
                new Node\Arg(
                    value: $this->columns,
                    name: new Node\Identifier('columns'),
                ),
            );
        }

        if ($this->logger !== null) {
            array_push(
                $arguments,
                new Node\Arg(
                    value: $this->logger,
                    name: new Node\Identifier('logger'),
                ),
            );
        }

        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(
                $this->safeMode
                    ? 'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor'
                    : 'Kiboko\\Component\\Flow\\Csv\\FingersCrossed\\Extractor'
            ),
            args: $arguments,
        );
    }
}
