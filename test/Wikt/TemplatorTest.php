<?php

namespace Test\Wikt;

use PHPUnit\Framework\TestCase;
use Wikt\Document\DocxDocument;
use Wikt\Templator;

class TemplatorTest extends TestCase {


    /**
     * @throws \Wikt\Exception\InvalidArgumentException
     */
    public function testDocxDocumentTemplator() {

        $cachePath = __DIR__ . '/temp/';
        $templator = new Templator($cachePath);

        $documentPath = __DIR__ . '/resources/document.docx';
        $document = new DocxDocument($documentPath);

        $values = array(
            'library' => 'Wikt',
            'simpleValue' => 'I am simple value',
            'nested' => array(
                'firstValue' => 'First child value',
                'secondValue' => 'Second child value'
            ),
            'header' => 'test of a table row',
            'students' => array(
                array('id' => 1, 'name' => 'Student 1', 'mark' => '10'),
                array('id' => 2, 'name' => 'Student 2', 'mark' => '4'),
                array('id' => 3, 'name' => 'Student 3', 'mark' => '7')
            ),
            'maxMark' => 10,
            'todo' => array(
                'TODO 1',
                'TODO 2',
                'TODO 3'
            )
        );
        $result = $templator->render($document, $values);

         $saved = $result->save(__DIR__ . '/results', 'result.docx');

         $this->assertTrue($saved);
    }
}
