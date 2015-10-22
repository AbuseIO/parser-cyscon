<?php

namespace AbuseIO\Parsers;

class Cyscon extends Parser
{
    /**
     * Create a new Cyscon instance
     */
    public function __construct($parsedMail, $arfMail)
    {
        parent::__construct($parsedMail, $arfMail, $this);
    }

    /**
     * Parse attachments
     * @return Array    Returns array with failed or success data
     *                  (See parser-common/src/Parser.php) for more info.
     */
    public function parse()
    {
        foreach ($this->parsedMail->getAttachments() as $attachment) {
            if (strpos($attachment->filename, '-report.txt') === false) {
                continue;
            }

            // Handle aliasses first
            foreach (config("{$this->configBase}.parser.aliases") as $alias => $real) {
                if ($attachment->filename == "{$alias}-report.txt") {
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
                        // Event has all requirements met, filter and add!
                        $report = $this->applyFilters($report);

                        $report['domain'] = preg_replace('/^www\./', '', $report['domain']);

                        $report['uri'] = parse_url($report['uri'], PHP_URL_PATH);

                        $this->events[] = [
                            'source'        => config("{$this->configBase}.parser.name"),
                            'ip'            => $report['ip'],
                            'domain'        => $report['domain'],
                            'uri'           => $report['uri'],
                            'class'         => config("{$this->configBase}.feeds.{$this->feedName}.class"),
                            'type'          => config("{$this->configBase}.feeds.{$this->feedName}.type"),
                            'timestamp'     => strtotime($report['last_seen']),
                            'information'   => json_encode($report),
                        ];
                    }
                } else { // Unable to build report
                    $this->warningCount++;
                }
            }
        }

        return $this->success();
    }
}
