<?php

namespace Wikt\Extension;

use DOMElement;
use Wikt\Extension\ExtensionInterface;
use Wikt\Processor\Tag;

abstract class Extension implements ExtensionInterface
{
    /**
     * @var Tag
     */
    protected $tag;
    /**
     * @inherit
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @inherit
     * @param array $arguments
     * @param DOMElement $node
     */
    public function execute(array $arguments, \DOMElement $node)
    {
        $arguments = $this->prepareArguments($arguments);
        $this->insertTemplateLogic($arguments, $node);
    }
    /**
     * Prepare / validate / merge with defaults / modify given arguments.
     * @param array $arguments
     * @return array
     */
    protected abstract function prepareArguments(array $arguments);
    /**
     * All template modification magic is here.
     * @param array $arguments
     * @param \DOMElement $node
     * @return void
     */
    protected abstract function insertTemplateLogic(array $arguments, \DOMElement $node);
}
