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
        $configBase = 'parsers.' . $reflect->getShortName();

        Log::info(
            get_class($this) . ': Received message from: ' .
            $this->parsedMail->getHeader('from') . " with subject: '" .
            $this->parsedMail->getHeader('subject') . "' arrived at parser: " .
            config("{$configBase}.parser.name")
        );

        $events = [ ];

        foreach ($this->parsedMail->getAttachments() as $attachment) {
            if (strpos($attachment->filename, '-report.txt') === false) {
                continue;
            }

            // Handle aliasses first
            $feedName = false;
            foreach (config("{$configBase}.parser.aliases") as $alias => $real) {
                if ($attachment->filename == "{$alias}-report.txt") {
                    $feedName = $real;
                }
            }

            if (empty(config("{$configBase}.feeds.{$feedName}"))) {
                return $this->failed("Detected feed '{$feedName}' is unknown.");
            }

            if (config("{$configBase}.feeds.{$feedName}.enabled") !== true) {
                continue;
            }

            $report = str_replace("\r", "", $attachment->getContent());
            preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/', $report, $regs);
            $fields = array_combine($regs[1], $regs[2]);

            $columns = array_filter(config("{$configBase}.feeds.{$feedName}.fields"));
            if (count($columns) > 0) {
                foreach ($columns as $column) {
                    if (!isset($fields[$column])) {
                        return $this->failed(
                            "Required field ${column} is missing in the report or config is incorrect."
                        );
                    }
                }
            }

            $fields['domain'] = preg_replace('/^www\./', '', $fields['domain']);

            $fields['uri'] = parse_url($fields['uri'], PHP_URL_PATH);

            $event = [
                'source'        => config("{$configBase}.parser.name"),
                'ip'            => $fields['ip'],
                'domain'        => $fields['domain'],
                'uri'           => $fields['uri'],
                'class'         => config("{$configBase}.feeds.{$feedName}.class"),
                'type'          => config("{$configBase}.feeds.{$feedName}.type"),
                'timestamp'     => strtotime($fields['last_seen']),
                'information'   => json_encode($fields),
            ];

            $events[] = $event;
        }

        return $this->success($events);
    }
}
