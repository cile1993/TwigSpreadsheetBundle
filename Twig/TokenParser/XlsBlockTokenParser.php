<?php

namespace MewesK\TwigExcelBundle\Twig\TokenParser;

use MewesK\TwigExcelBundle\Twig\NodeHelper;
use Twig_Error_Syntax;
use Twig_Node_Block;
use Twig_Node_BlockReference;
use Twig_Parser;
use Twig_Token;
use Twig_TokenParser;
use Twig_TokenParser_Block;

/**
 * Class XlsBlockTokenParser
 *
 * @package MewesK\TwigExcelBundle\Twig\TokenParser
 */
class XlsBlockTokenParser extends Twig_TokenParser
{
    private $baseTokenParser;

    public function __construct()
    {
        // instantiate Twig block parser since final classes cannot be inherited (Twig 2.x fix)
        $this->baseTokenParser = new Twig_TokenParser_Block();
    }

    /**
     * @param Twig_Token $token
     * @return Twig_Node_BlockReference
     * @throws Twig_Error_Syntax
     */
    public function parse(Twig_Token $token)
    {
        /**
         * @var Twig_Node_BlockReference $blockReference
         */
        $blockReference = $this->baseTokenParser->parse($token);
        /**
         * @var Twig_Node_Block $block
         */
        $block = $this->parser->getBlock($blockReference->getAttribute('name'));

        // prepare block
        NodeHelper::removeTextNodesRecursively($block, $this->parser);
        NodeHelper::fixMacroCallsRecursively($block);

        // mark for syntax checks
        foreach ($block->getIterator() as $node) {
            if ($node instanceof Twig_Node_Block) {
                $node->setAttribute('twigExcelBundle', true);
            }
        }

        return $blockReference;
    }

    /**
     * @param Twig_Token $token
     * @return bool
     */
    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endxlsblock');
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'xlsblock';
    }

    public function setParser(Twig_Parser $parser)
    {
        parent::setParser($parser);
        $this->baseTokenParser->setParser($parser);
    }
}
