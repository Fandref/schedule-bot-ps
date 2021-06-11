<?php

return [
    "NPUD" => [
        "group" => [
            "subject" => "/^(.[^<br>]+)\s\(/",
            "subject_type" => "/^.[^<br>]+\((.[^)]+|.)\)<br>/",
            "teacher" => "/<br>\s+(.[^<br>]+[A-ZАЄ-ЯҐ]\.)\s/",
            "audience" => "/[A-ZАЄ-ЯҐ]\.\s+(.[^<br>\n]+)/"
        ],

        "teacher" => [
            "subject" => "/^(.[^<br>]+)\s\(/",
            "subject_type" => "/^.[^<br>]+\((.[^)]+|.)\)<br>/",
            "group" => "/^.[^\)]+\)<br>\s*(.[^<br>]+)<br>/",
            "audience" => "/(?:.[^<br>]+<br>){2}(.[^<br>]+)/"
        ],
    ]
];