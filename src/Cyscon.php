<?php

namespace AbuseIO\Parsers;

use AbuseIO\Models\Incident;

/**
 * Class Cyscon
 * @package AbuseIO\Parsers
 */
class Cyscon extends Parser
{
    /**
     * Create a new Cyscon instance
     *
     * @param \PhpMimeMailParser\Parser $parsedMail phpMimeParser object
     * @param array $arfMail array with ARF detected results
     */
    public function __construct($parsedMail, $arfMail)
    {
        parent::__construct($parsedMail, $arfMail, $this);
    }

    /**
     * Parse attachments
     * @return array    Returns array with failed or success data
     *                  (See parser-common/src/Parser.php) for more info.
     */
    public function parse()
    {
        foreach ($this->parsedMail->getAttachments() as $attachment) {
            if (strpos($attachment->getFilename(), '-report.txt') === false) {
                continue;
            }

            // Handle aliasses first
            foreach (config("{$this->configBase}.parser.aliases") as $alias => $real) {
                if ($attachment->getFilename() == "{$alias}-report.txt") {
                    $this->feedName = $real;
                    break;
                }
            }

            if ($this->isKnownFeed() && $this->isEnabledFeed()) {
                // Sanity check
                $report = str_replace("\r", "", $attachment->getContent());

                if (preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/', $report, $matches)) {
                    $report = array_combine($matches[1], $matches[2]);

                    if ($this->hasRequiredFields($report) === true) {
                        // incident has all requirements met, filter and add!
                        $report = $this->applyFilters($report);

                        $incident = new Incident();
                        $incident->source      = config("{$this->configBase}.parser.name");
                        $incident->source_id   = false;
                        $incident->ip          = $report['ip'];
                        $incident->domain      = empty($report['uri']) ? false : getDomain($report['uri']);
                        $incident->class       = config("{$this->configBase}.feeds.{$this->feedName}.class");
                        $incident->type        = config("{$this->configBase}.feeds.{$this->feedName}.type");
                        $incident->timestamp   = strtotime($report['last_seen']);
                        $incident->information = json_encode($report);

                        $this->incidents[] = $incident;
                    }
                } else { // Unable to build report
                    $this->warningCount++;
                }
            }
        }

        return $this->success();
    }
}
