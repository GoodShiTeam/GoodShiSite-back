<?php

// src/Logger/SQLLogger.php
namespace App\Logger;

use Doctrine\DBAL\Logging\SQLLogger;
use Psr\Log\LoggerInterface;

class DoctrineSQLLogger implements SQLLogger
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function startQuery($sql, ?array $params = null, ?array $types = null): void
    {
        $this->logger->info($sql, ['params' => $params, 'types' => $types]);
    }

    public function stopQuery(): void
    {
        // No operation needed here
    }
}
