<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\Node;

use Twig\Compiler as Twig_Compiler;

/**
 * Class RowNode.
 */
class RowNode extends BaseNode
{
    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler): void
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write(self::CODE_INSTANCE.'->startRow(')
                ->subcompile($this->getNode('index'))
            ->raw(');'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write(self::CODE_INSTANCE.'->endRow();'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [
            SheetNode::class,
        ];
    }
}
