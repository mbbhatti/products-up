<?php

namespace App\Command;

use App\Service\GoogleSpreadsheet;
use App\Service\XmlParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SpreadsheetCommand
 * @package App\Command
 */
class SpreadsheetCommand extends Command
{
    /**
     * @var XmlParser
     */
    private $xml;

    /**
     * @var GoogleSpreadsheet
     */
    private $googleSpreadsheet;

    /**
     * @var string
     */
    protected static $defaultName = 'spreadsheet';

    /**
     * SpreadsheetCommand constructor.
     *
     * @param XmlParser $xml
     * @param GoogleSpreadsheet $googleSpreadsheet
     */
    public function __construct(XmlParser $xml, GoogleSpreadsheet $googleSpreadsheet)
    {
        $this->xml = $xml;
        $this->googleSpreadsheet = $googleSpreadsheet;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('The program should push XML file data to a Google Spreadsheet');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $data = $this->xml->parse();
        if (empty($data)) {
            $io->error('Provided data is not appropriate');

            return 0;
        }

        if ($this->googleSpreadsheet->create($data)) {
            $io->success('Data has been pushed to a Spreadsheet');
        } else {
            $io->error('Data never pushed to a Spreadsheet');
        }

        return 0;
    }
}
