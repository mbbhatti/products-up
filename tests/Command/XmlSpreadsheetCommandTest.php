<?php

namespace App\Tests\Unit\Command;

use App\Command\SpreadsheetCommand;
use App\Service\GoogleSpreadsheet;
use App\Service\XmlParser;
use App\Tests\Utils\TestUtils;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class XmlSpreadsheetCommandTest extends KernelTestCase
{
    public function testExecuteFailWithoutData()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $xmlParser = new XmlParser($_ENV['EMPTY_FILE'], $logger);

        $googleSheet = new GoogleSpreadsheet(
            TestUtils::getService(),
            $_ENV['GOOGLE_SHEET_ID'],
            $_ENV['GOOGLE_SHEET_RANGE'],
            $logger
        );
        $xmlCommand = new SpreadsheetCommand($xmlParser, $googleSheet);

        $kernel = static::createKernel();
        $application = new Application($kernel);
        $application->add($xmlCommand);

        $command = $application->find('spreadsheet');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'  => $command->getName()]);
        $output = $commandTester->getDisplay();
        $output = trim(preg_replace('/\s\s+/', ' ', $output));

        $this->assertEquals('[ERROR] Provided data is not appropriate', $output);
    }

    public function testExecuteFailWithoutSheetID()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $xmlParser = new XmlParser($_ENV['TEST_FILE'], $logger);

        $googleSheet = new GoogleSpreadsheet(
            TestUtils::getService(),
            '',
            $_ENV['GOOGLE_SHEET_RANGE'],
            $logger
        );
        $xmlCommand = new SpreadsheetCommand($xmlParser, $googleSheet);

        $kernel = static::createKernel();
        $application = new Application($kernel);
        $application->add($xmlCommand);

        $command = $application->find('spreadsheet');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'  => $command->getName()]);
        $output = $commandTester->getDisplay();
        $output = trim(preg_replace('/\s\s+/', ' ', $output));

        $this->assertEquals('[ERROR] Data never pushed to a Spreadsheet', $output);
    }

    public function testExecuteFailWithoutSheetRange()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $xmlParser = new XmlParser($_ENV['TEST_FILE'], $logger);

        $googleSheet = new GoogleSpreadsheet(
            TestUtils::getService(),
            $_ENV['GOOGLE_SHEET_ID'],
            '',
            $logger
        );
        $xmlCommand = new SpreadsheetCommand($xmlParser, $googleSheet);

        $kernel = static::createKernel();
        $application = new Application($kernel);
        $application->add($xmlCommand);

        $command = $application->find('spreadsheet');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'  => $command->getName()]);
        $output = $commandTester->getDisplay();
        $output = trim(preg_replace('/\s\s+/', ' ', $output));

        $this->assertEquals('[ERROR] Data never pushed to a Spreadsheet', $output);
    }

    public function testExecuteSuccess()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $xmlParser = new XmlParser($_ENV['TEST_FILE'], $logger);

        $googleSheet = new GoogleSpreadsheet(
            TestUtils::getService(),
            $_ENV['GOOGLE_SHEET_ID'],
            $_ENV['GOOGLE_SHEET_RANGE'],
            $logger
        );
        $xmlCommand = new SpreadsheetCommand($xmlParser, $googleSheet);

        $kernel = static::createKernel();
        $application = new Application($kernel);
        $application->add($xmlCommand);

        $command = $application->find('spreadsheet');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command'  => $command->getName()]);
        $output = $commandTester->getDisplay();
        $output = trim(preg_replace('/\s\s+/', ' ', $output));

        $this->assertEquals('[OK] Data has been pushed to a Spreadsheet', $output);
    }
}

