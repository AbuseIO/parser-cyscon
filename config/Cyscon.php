<?php

return [
    'parser' => [
        'name'          => 'CSIRT',
        'enabled'       => true,
        'sender_map'    => [
            '/abuse-reports@cyscon.de/',
        ],
        'body_map'      => [
            //
        ],
        'aliases'       => [
            'SPAMVERTIZED'  => 'Spamvertized',
            'MALWARE'       => 'Malware',
            'PHISH'         => 'Phishing',
            'DEFACEMENT'    => 'Defacement',
            'ADWARE'        => 'Malicious adware',
            'FRAUD'         => 'Fraud',
            'MALICIOUS'     => 'Malicious',
            'EVIL'          => 'Evil',
            'SCAM'          => 'Scam',
            'BLACKLISTED'   => 'Malware',
            'SUSPICIOUS'    => 'Suspicious',
        ],
    ],

    'feeds' => [
        'Suspicious' => [
            'class'     => 'COMPROMISED_WEBSITE',
            'type'      => 'Info',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],
        'Spamvertized' => [
            'class'     => 'COMPROMISED_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Malware' => [
            'class'     => 'COMPROMISED_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Phishing' => [
            'class'     => 'PHISING_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Defacement' => [
            'class'     => 'COMPROMISED_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Malicious adware' => [
            'class'     => 'COMPROMISED_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Fraud' => [
            'class'     => 'PHISING_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Malicious' => [
            'class'     => 'COMPROMISED_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Scam' => [
            'class'     => 'PHISING_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

        'Evil' => [
            'class'     => 'COMPROMISED_WEBSITE',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'ip',
                'domain',
                'last_seen',
                'uri',
            ],
        ],

    ],
];
