<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\Node;

use Erelke\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use Twig\Compiler as Twig_Compiler;

/**
 * Class DocumentNode.
 */
class DocumentNode extends BaseNode
{
    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler): void
    {
        $compiler->addDebugInfo($this)
            ->write("ob_start();\n")
            ->write(self::CODE_INSTANCE.' = new '.PhpSpreadsheetWrapper::class.'($context, $this->env, ')
                ->repr($this->attributes)
            ->raw(');'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startDocument(')
                ->subcompile($this->getNode('properties'))
            ->raw(');'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write("ob_end_clean();\n")
            ->write(self::CODE_INSTANCE.'->endDocument();'.PHP_EOL)
            ->write('unset('.self::CODE_INSTANCE.');'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [];
    }
}
