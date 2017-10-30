<?php

return [
    
    'nlp' => [
        'tokenizers' => [
            'law-fr'    => 'classic',
            'nonlaw-fr' => 'classic',
        ],
        'models' => [
            'law-fr' => [
                'cache'     => env('NLP_MODEL_CACHE', false),
                'store'     => env('NLP_MODEL_STORE', true),
                'domains'   => ['law-fr'],
                'tokenizer' => 'classic',
                'classesFromTags' => true,
            ],
            'is_law-fr' => [
                'cache'     => env('NLP_MODEL_CACHE', false),
                'store'     => env('NLP_MODEL_STORE', true),
                'domains'   => ['law-fr', 'nonlaw-fr'],
                'classes'   => ['true', 'false'],
                'tokenizer' => 'classic',
            ],
        ],
    ],

    // later: origin-specific
    // all values in minutes
    'curation' => [
        'twitterAccount' => env('TWITTER_ACCOUNT', 'hiyounes'),
        'sourceAge'      => env('CUR_SOURCE_AGE', 60 * 3),
        'linkAge'        => env('CUR_LINK_AGE', 60 * 24), // get X min old links
        'publishDelay'   => env('CUR_PUBLISH_DELAY', 30),
        // 'postsPerHour' => 50,
    ],

    // later: domain specific, mettre dans sqlite
    'tweeps' => [
        
       'keywords' => [

            'desc_in' => [
                'avocat',
                'barreau',
                'droit',
                'huissier',
                'justice',
                'attorney',
                'lawyer',
                'judiciaire',
                'juridique',
                'juriste',
                'magistrat',
                'notaire',
                'stage',
            ],

            'desc_out' => [
                '#fn',
                'patriote',
                'réactionnaire',
                'souche',
                'sioniste',
                'islamisation',
                'palestine',
                'eleve',
                'etudiant',
                'student',
                'licence de droit',
                'master',
                'm2',
                'm1',
                'l3',
                'l2',
                'l1',
                'droit des femmes',
                'droit des animaux',
                'journaliste',
                'engagent qu',
                'militant',
                'eleve',
            ],

            'location_out' => [
                'abidjan', 
                'afrique', 
                'alger', 
                'algiers',
                'bamako',
                'belgique', 
                'belgium',
                'benin', 
                'bruxelles',
                'burkina', 
                'cameroun', 
                'canada',
                'casablanca', 
                'conakry', 
                'congo', 
                'dakar', 
                'douala', 
                'geneve', 
                'guinee', 
                'ivoire', 
                'kinshasa', 
                'laval',
                'liege', 
                'london', 
                'londres', 
                'mali',
                'maroc', 
                'morocco',
                'montreal', 
                'ouagadougou', 
                'quebec', 
                'rwanda',
                'suisse', 
                'switzerland',
                'senegal', 
                'togo', 
                'tunis', 
                'zurich',
            ]
        ],
    ],
];
