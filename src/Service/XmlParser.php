<?php

namespace App\Service;

use phpseclib3\Exception\FileNotFoundException;
use Psr\Log\LoggerInterface;
use Exception;
use Symfony\Component\Config\Util\Exception\InvalidXmlException;

/**
 * Class XmlParser
 * @package App\Service
 */
class XmlParser
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * XmlProcess constructor.
     *
     * @param string $file
     * @param LoggerInterface $logger
     */
    public function __construct(string $file, LoggerInterface $logger)
    {
        $this->file = $file;
        $this->logger = $logger;
    }

    /**
     * Read and parse xml file data
     *
     * @return array
     */
    public function parse(): array
    {
        $items = [];
        try {
            if (empty($this->file)) {
                throw new FileNotFoundException('File not found.');
            }

            // Fetching file contents
            libxml_use_internal_errors(true);
            $xml = simplexml_load_file($this->file);
            if (false === $xml) {
                $errors = [];
                foreach(libxml_get_errors() as $error) {
                    $errors[] = $error->message;
                }
                throw new InvalidXmlException(json_encode($errors));
            }

            // Parse xml file data
            foreach ($xml as $attributeName => $attributeValue) {
                $item = [];

                // Getting name of the columns
                if (empty($items)) {
                    $items[] = array_keys(get_object_vars($attributeValue));
                }

                // Getting attributes as data
                foreach ($attributeValue as $key => $value) {
                    $item[] = (string)$value;
                }
                $items[] = $item;
            }
        } catch (Exception | FileNotFoundException | InvalidXmlException $e) {
            $this->logger->error('Message: ' .$e->getMessage());
        }

        return $items;
    }
}