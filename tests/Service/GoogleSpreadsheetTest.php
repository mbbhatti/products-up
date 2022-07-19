<?php

namespace App\Tests\Service;

use App\Service\GoogleSpreadsheet;
use App\Service\XmlParser;
use App\Tests\Utils\TestUtils;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GoogleSpreadsheetTest extends TestCase
{
    public function testCreateFailWithoutData()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $googleSheet = new GoogleSpreadsheet(TestUtils::getService(),'test', 'test', $logger);
        $response = $googleSheet->create([]);

        $this->assertFalse($response, 'Data is empty');
    }

    public function testParseFailWithInvalidData()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $xmlParser = new XmlParser($_ENV['TEST_FILE'], $logger);
        $content = $xmlParser->parse();

        $googleSheet = new GoogleSpreadsheet(TestUtils::getService(), 'test', 'test', $logger);
        $response = $googleSheet->create($content);

        $this->assertFalse($response, '404 not found');
    }

    public function testParseSuccess()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $xmlParser = new XmlParser($_ENV['TEST_FILE'], $logger);
        $content = $xmlParser->parse();

        $googleSheet = new GoogleSpreadsheet(
            TestUtils::getService(),
            $_ENV['GOOGLE_SHEET_ID'],
            $_ENV['GOOGLE_SHEET_RANGE'],
            $logger
        );
        $response = $googleSheet->create($content);

        $this->assertTrue($response, 'Process completed');
    }
}

