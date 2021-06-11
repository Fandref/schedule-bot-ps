<?php



return [
    "Швидкий розклад\s?|\s🚀?" => [
        'handler' => 'onMessage',
        'action' => 'quickSchedule'
    ]
];

