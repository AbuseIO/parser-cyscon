<?php

namespace AbuseIO\Parsers;

use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Filter;
use Log;
use ReflectionClass;

class Cyscon extends Parser
{
    public $parsedMail;
    public $arfMail;

    /**
     * Create a new Blocklistde instance
     */
    public function __construct($parsedMail, $arfMail)
    {
        $this->parsedMail = $parsedMail;
        $this->arfMail = $arfMail;
    }

    /**
     * Parse attachments
     * @return Array    Returns array with failed or success data
     *                  (See parser-common/src/Parser.php) for more info.
     */
    public function parse()
    {
        // Generalize the local config based on the parser class name.
        $reflect = new ReflectionClass($this);
        $this->configBase = 'parsers.' . $reflect->getShortName();

        Log::info(
            get_class($this) . ': Received message from: ' .
            $this->parsedMail->getHeader('from') . " with subject: '" .
            $this->parsedMail->getHeader('subject') . "' arrived at parser: " .
            config("{$this->configBase}.parser.name")
        );

        $events = [ ];

        foreach ($this->parsedMail->getAttachments() as $attachment) {
            if (strpos($attachment->filename, '-report.txt') === false) {
                continue;
            }

            // Handle aliasses first
            $this->feedName = false;
            foreach (config("{$this->configBase}.parser.aliases") as $alias => $real) {
                if ($attachment->filename == "{$alias}-report.txt") {
                    $this->feedName = $real;
                }
            }

            if (!$this->isKnownFeed()) {
                return $this->failed(
                    "Detected feed {$this->feedName} is unknown."
                );
            }

            if (!$this->isEnabledFeed()) {
                continue;
            }

            $report = str_replace("\r", "", $attachment->getContent());
            preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/', $report, $regs);
            $report = array_combine($regs[1], $regs[2]);

            if (!$this->hasRequiredFields($report)) {
                return $this->failed(
                    "Required field {$this->requiredField} is missing or the config is incorrect."
                );
            }

            $report = $this->applyFilters($report);

            $report['domain'] = preg_replace('/^www\./', '', $report['domain']);

            $report['uri'] = parse_url($report['uri'], PHP_URL_PATH);

            $event = [
                'source'        => config("{$this->configBase}.parser.name"),
                'ip'            => $report['ip'],
                'domain'        => $report['domain'],
                'uri'           => $report['uri'],
                'class'         => config("{$this->configBase}.feeds.{$this->feedName}.class"),
                'type'          => config("{$this->configBase}.feeds.{$this->feedName}.type"),
                'timestamp'     => strtotime($report['last_seen']),
                'information'   => json_encode($report),
            ];

            $events[] = $event;
        }

        return $this->success($events);
    }
}
