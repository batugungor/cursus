<?php

namespace app\Fields;

class CourseFields
{
    public static function getLessons($array = []): ?array
    {
        $query = get_posts([
            'post_type' => 'lessons',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);

        foreach ($query as $query_selected) {
            $array[$query_selected[$identifier]] = $query_selected[$key];
        }

        return $array;
    }
}
