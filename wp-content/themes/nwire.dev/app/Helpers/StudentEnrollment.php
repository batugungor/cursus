<?php

namespace app\Helpers;

use app\PostTypes\Course;
use Rareloop\Lumberjack\Post;
use WP_User;

class StudentEnrollment
{
    public static string $meta = 'lesson_access';

    /**
     * @param WP_User $user
     * @return array|string
     *
     * Returns a list of all courses (and it's associations) where a student has access to
     */
    public static function get_student_association(WP_User $user): array|string
    {
        return get_user_meta($user->ID, self::$meta, true);
    }

    public static function check_if_student_in_course(WP_User $user, Course $course, ?int $association_id = null): bool
    {
        if (!$user || !$course) {
            return false;
        }

        $enrollment = self::get_student_association($user);

        if (!is_array($enrollment)) {
            return false;
        }

        if (!array_key_exists($course->ID, $enrollment)) {
            return false;
        }

        if (is_null($association_id)) {
            return true;
        }

        return in_array($association_id, $enrollment[$course->ID]);
    }

    public static function add_student_to_course(WP_User $user, Course $course, ?int $association_id = null): bool
    {
        if (!$user || !$course) {
            return false;
        }

        $enrollment = self::get_student_association($user);

        if (is_string($enrollment)) {
            $enrollment = [];
        }

        if (!self::check_if_student_in_course($user, $course) && is_array($enrollment)) {
            $enrollment[$course->ID] = [];
        }

        if (!is_null($association_id) && !in_array($association_id, $enrollment[$course->ID])) {
            $enrollment[$course->ID][] = $association_id;
        }

        update_user_meta($user->ID, self::$meta, $enrollment);

        return true;
    }


    public static function remove_student_from_course(WP_User $user, Course $course, ?int $association_id = null): bool
    {
        if (!$user || !$course) {
            return false;
        }

        $enrollment = self::get_student_association($user);

        if (is_string($enrollment)) {
            return false;
        }

        if (self::check_if_student_in_course($user, $course) && is_array($enrollment)) {
            if (!is_null($association_id)) {
                $index = array_search($association_id, $enrollment[$course->ID]);
                if ($index !== false) {
                    unset($enrollment[$course->ID][$index]);
                }
            }

            if (is_null($association_id)) {
                unset($enrollment[$course->ID]);
            }
        }

        update_user_meta($user->ID, self::$meta, $enrollment);

        return true;
    }

    public static function get_association(int $association_id): Post
    {
        return new Post($association_id);
    }

    public static function get_all_students_by_course(Course $course, ?int $association_id = null): array
    {
        $students = [];

        $enrolled_users = get_users([
            'meta_key'     => self::$meta,
            'meta_value'   => $course->ID,
            'meta_compare' => 'LIKE',
        ]);

        foreach ($enrolled_users as $user) {
            $enrollment = get_user_meta($user->ID, self::$meta, true);

            if (is_array($enrollment) && isset($enrollment[$course->ID])) {
                if (!is_null($association_id)) {
                    if (in_array($association_id, $enrollment[$course->ID])) {
                        $students[] = $user;
                    }
                } else {
                    $students[] = $user;
                }
            }
        }

        return $students;
    }
}
