<?php

namespace Wikt\Processor;

class Tag
{
    /**
     * Tag data.
     *
     * @var array
     */
    private $summary;

    /**
     * Path to value in placeholder.
     *
     * @var array
     */
    private $path;

    /**
     * Parsed functions.
     *
     * @var array
     */
    private $functions;

    /**
     * Tag constructor.
     * @param array $summary
     * @param array $path
     * @param array $functions
     */
    public function __construct(array $summary, array $path, array $functions)
    {
        $this->summary = $summary;
        $this->path = $path;
        $this->functions = $functions;
    }

    /**
     * Placeholder position inside node content.
     * Left padding of string.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->summary['position'];
    }

    /**
     * Length of tag with brackets.
     *
     * @return int
     */
    public function getLength()
    {
        return $this->summary['length'];
    }

    /**
     * XPath presentation of value path.
     *
     * @return string
     */
    public function getXmlPath()
    {
        return implode('/', $this->path);
    }

    /**
     * Last part of the path.
     *
     * @return string
     */
    public function getRelativePath()
    {
        return end($this->path);
    }

    /**
     * Has function helper.
     *
     * @return bool
     */
    public function hasFunctions()
    {
        return (count($this->functions) !== 0);
    }

    /**
     * Get parsed functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Get placeholder content with brackets.
     *
     * @return string
     */
    public function getTextContent()
    {
        return $this->summary['textContent'];
    }
}
