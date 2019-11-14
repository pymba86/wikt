<?php

namespace Wikt\Document\Docx\Extension;

use Wikt\Exception\ExtensionException;
use Wikt\Exception\ParsingException;
use Wikt\Extension\Extension;
use Wikt\Processor;
use Wikt\XMLHelper;

class ListItem extends Extension
{
    /**
     * @inherit
     * @throws ExtensionException
     */
    protected function prepareArguments(array $arguments)
    {
        if (count($arguments) !== 0) {
            throw new ExtensionException('Wrong arguments number, 0 needed, got ' . count($arguments));
        }
        return $arguments;
    }

    /**
     * @inherit
     * @param array $arguments
     * @param \DOMElement $node
     * @throws ExtensionException
     * @throws ParsingException
     */
    protected function insertTemplateLogic(array $arguments, \DOMElement $node)
    {
        $template = $node->ownerDocument;
        $listName = $this->tag->getRelativePath();
        // find existing or initiate new table row template
        if ($this->isListItemTemplateExist($listName, $template) === false) {
            $rowTemplate = $template->createElementNS(Processor::XSL_NS, 'xsl:template');
            $rowTemplate->setAttribute('name', $listName);
            // find row node
            $rowNode = XMLHelper::parentUntil('w:p', $node);
            // call-template for each row
            $foreachNode = $template->createElementNS(Processor::XSL_NS, 'xsl:for-each');
            $foreachNode->setAttribute('select', '/' . Processor::VALUE_NODE . '/' . $listName . '/item');
            $callTemplateNode = $template->createElementNS(Processor::XSL_NS, 'xsl:call-template');
            $callTemplateNode->setAttribute('name', $listName);
            $foreachNode->appendChild($callTemplateNode);
            // insert call-template before moving
            $rowNode->parentNode->insertBefore($foreachNode, $rowNode);
            // move node into row template
            $rowTemplate->appendChild($rowNode);
            $template->documentElement->appendChild($rowTemplate);
        }
        // FIXME пофиксить повторное использование функции
        Processor::insertTemplateLogic($this->tag->getTextContent(), '.', $node);
    }

    /**
     * @param $rowName
     * @param \DOMDocument $template
     * @return bool
     * @throws ExtensionException
     */
    private function isListItemTemplateExist($rowName, \DOMDocument $template)
    {
        $xpath = new \DOMXPath($template);
        $nodeList = $xpath->query('/xsl:stylesheet/xsl:template[@name="' . $rowName . '"]');
        if ($nodeList->length > 1) {
            throw new ExtensionException('Unexpected template count.');
        }
        return ($nodeList->length === 1);
    }

}
