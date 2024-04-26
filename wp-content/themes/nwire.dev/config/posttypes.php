<?php

return [
    /**
     * List all the sub-classes of Rareloop\Lumberjack\Post in your app that you wish to
     * automatically register with WordPress as part of the bootstrap process.
     */
    'register' => [
        App\PostTypes\Block::class,

        App\PostTypes\Course::class,
        App\PostTypes\Lesson::class,
        App\PostTypes\Quiz::class,
    ],
];
