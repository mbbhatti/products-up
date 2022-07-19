<?php

namespace App\Service;

use Google\Service;
use Exception;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\ValueRange;
use PHPUnit\Framework\InvalidDataProviderException;
use Psr\Log\LoggerInterface;

/**
 * Class GoogleSpreadsheet
 * @package App\Service
 */
class GoogleSpreadsheet
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $sheetId;

    /**
     * @var string
     */
    private $sheetRange;

    /**
     * GoogleSpreadsheet constructor.
     *
     * @param Service $service
     * @param string $sheetId
     * @param string $sheetRange
     * @param LoggerInterface $logger
     */
    public function __construct(Service $service, string $sheetId, string $sheetRange, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->sheetId = $sheetId;
        $this->sheetRange = $sheetRange;
        $this->logger = $logger;
    }

    /**
     * Make entries in the spreadsheet
     *
     * @param array $values
     * @return bool
     */
    public function create(array $values): bool
    {
        try{
            if (empty($values)) {
                throw new InvalidDataProviderException('Data can not be empty.');
            }

            // Executing the request to clear old data
            $this->service->spreadsheets_values->clear($this->sheetId, $this->sheetRange, new ClearValuesRequest());

            // Prepare request data
            $body = new ValueRange(['values' => $values]);
            $params = ['valueInputOption' => 'RAW'];

            // Executing the request to update data
            $result = $this->service->spreadsheets_values->update($this->sheetId, $this->sheetRange, $body, $params);

            // Show response in log
            $this->logger->info(sprintf("%d rows updated.", $result->getUpdatedRows()));

            return true;
        } catch(Exception | InvalidDataProviderException $e) {
            $this->logger->error('Message: ' .$e->getMessage());
        }

        return false;
    }
}

