<?php

namespace Sphinx;

/**
 * Sphinx logger.
 */
class Logger
{
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARN = 'WARN';
    const LEVEL_ERROR = 'ERROR';

    /**
     * @var string Path to log to.
     */
    protected $logpath;

    /**
     * @var bool Should debug messages be logged?
     */
    protected $debug = false;

    /**
     * Logger constructor.
     *
     * @param string $logpath Path to logger
     * @param bool $debug Log debug messages?
     */
    public function __construct($logpath, $debug = false) {
        $this->logpath = $logpath;
        $this->debug = $debug;
    }

    /**
     * Log an entry to the log file.
     *
     * @param string $level Logging level
     * @param string $message Message to log.
     */
    public function log($level, $message) {
        if ($this->debug && ($level == self::LEVEL_DEBUG)) {
            return; // debug logging is disabled.
        }

        // Generate message
        $timestamp = date('c');
        $message = "[$timestamp] [$level]: $message";

        // Append to file.
        $file = fopen($this->logpath, 'a');
        fwrite($file, $message . "\r\n");
        fclose($file);
    }

    /**
     * Debug message.
     *
     * @param string $message
     */
    public function debug($message) {
        $this->log(SELF::LEVEL_DEBUG, $message);
    }

    /**
     * Informative messages.
     *
     * @param string $message
     */
    public function info($message) {
        $this->log(self::LEVEL_INFO, $message);
    }

    /**
     * Warnings the need the users notice.
     *
     * @param string $message
     */
    public function warn($message) {
        $this->log(self::LEVEL_WARN, $message);
    }

    /**
     * Error messages that require human intervention.
     *
     * @param string $message
     */
    public function error($message) {
        $this->log(self::LEVEL_ERROR, $message);
    }
}
