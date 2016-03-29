<?php namespace Neomerx\LimoncelloIlluminate\Logs;

use Psr\Log\LoggerInterface;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
trait LoggerTrait
{
    /**
     * @var LoggerInterface
     */
    private $loggerInstance;

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        if ($this->loggerInstance === null) {
            $this->loggerInstance = app(LoggerInterface::class);
        }

        return $this->loggerInstance;
    }
}
