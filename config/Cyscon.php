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
            'class'     => 'Compromised website',
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
            'class'     => 'Compromised website',
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
            'class'     => 'Compromised website',
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
            'class'     => 'Phishing website',
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
            'class'     => 'Compromised website',
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
            'class'     => 'Compromised website',
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
            'class'     => 'Phishing website',
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
            'class'     => 'Compromised website',
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
            'class'     => 'Phishing website',
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
            'class'     => 'Compromised website',
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
