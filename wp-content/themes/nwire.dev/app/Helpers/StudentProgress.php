<?php

namespace app\Helpers;

use app\PostTypes\Course;
use WP_User;

class StudentProgress
{
    public static string $meta = 'lesson_progress';

    public static function get_student_progress(WP_User $user): array|string
    {
        return get_user_meta($user->ID, self::$meta, true);
    }

    public static function check_if_student_finished(WP_User $user, Course $course, ?int $association_id = null): bool
    {
        $progress = self::get_student_progress($user);

        if (is_string($progress)) {
            return false;
        }

        if (!array_key_exists($course->ID, $progress)) {
            return false;
        }

        $enrollment = StudentEnrollment::get_student_association($user)[$course->ID];

        if (!is_null($association_id)) {
            if (array_key_exists($association_id, $progress[$course->ID])) {
                return $progress[$course->ID][$association_id];
            }
            return false;
        }

        foreach ($enrollment as $association) {
            if (!$progress[$course->ID][$association]) {
                return false;
            }
        }

        return true;
    }

    public static function set_student_progress(WP_User $user, Course $course, int $association_id, bool $finished): bool
    {
        $progress = self::get_student_progress($user);

        if (is_string($progress)) {
            $progress = [];
        }

        $progress[$course->ID][$association_id] = $finished;

        update_user_meta($user->ID, self::$meta, $progress);

        return true;
    }

    public static function reset_student_progress(WP_User $user, ?Course $course, ?int $association_id = null): bool
    {
        $progress = self::get_student_progress($user);

        if (is_string($progress) || !array_key_exists($course->ID, $progress)) {
            return false;
        }

        if (!is_null($course) && is_null($association_id)) {
            unset($progress[$course->ID]);
        }

        if (!is_null($course) && !is_null($association_id)) {
            unset($progress[$course->ID][$association_id]);
        }

        if ((is_null($course) && is_null($association_id)) || empty($progress)) {
            delete_user_meta($user->ID, self::$meta);

            return true;
        }

        update_user_meta($user->ID, self::$meta, $progress);

        return true;
    }
}
