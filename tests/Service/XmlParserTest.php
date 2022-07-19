<?php

namespace App\Tests\Service;

use App\Service\XmlParser;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class XmlParserTest extends TestCase
{
    public function testParseFailWithoutFile()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $xmlParser = new XmlParser('', $logger);
        $content = $xmlParser->parse();

        $this->assertEmpty($content);
    }

    public function testParseFailWithEmptyFile()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $xmlParser = new XmlParser($_ENV['EMPTY_FILE'], $logger);
        $content = $xmlParser->parse();

        $this->assertEmpty($content);
    }

    public function testParseSuccess()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $xmlParser = new XmlParser($_ENV['TEST_FILE'], $logger);
        $content = $xmlParser->parse();

        $this->assertGreaterThanOrEqual(0, $content, 'Getting xml data after parsing');
    }
}

