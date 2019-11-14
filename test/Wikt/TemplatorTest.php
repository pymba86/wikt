<?php

namespace Test\Wikt;

use PHPUnit\Framework\TestCase;
use Wikt\Document\DocxDocument;
use Wikt\Templator;

class TemplatorTest extends TestCase
{


    /**
     * @throws \Wikt\Exception\InvalidArgumentException
     */
    public function testDocxDocumentTemplator()
    {

        $cachePath = __DIR__ . '/temp/';
        $templator = new Templator($cachePath);
        $templator->trackDocument = true;
        $templator->debug =true;

        $documentPath = __DIR__ . '/resources/document.docx';
        $document = new DocxDocument($documentPath);

        $values = array(
            'medical_institutuion' => 'Бюджетное учреждение Ханты-Мансийского автономного округа - Югры "Окружная клиническая больница"',
            'member' => [
                'organization' => 'ОАО "Водоканал"',
                'fio' => 'Журавлев Иван Сергеевич',
                'contacts' => '895042335675, hic@vodokanal.ru'
            ],
            'date' => date("d.m.y"),
            'maintenance' => [
                'route_name' => 'г. Ханты-Мансийск - г. Горноправдинск',
                'event_name' => 'соревнование по волейболу(16-18 лет)',
                'date' => [
                    'start' => '25.11.2019',
                    'end' => '30.11.2019',
                ]
            ],
            'children_group' => [
                'name' => 'школьники(9-11 класс)',
                'num' => 30
            ]
        );
        $result = $templator->render($document, $values);

        $saved = $result->save(__DIR__ . '/results', 'template.docx');

        $this->assertTrue($saved);
    }
}
