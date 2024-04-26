<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

namespace App;

use App\Http\Controllers\Controller;
use app\PostTypes\Course;
use app\ViewModels\CourseViewModel;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use app\Helpers\StudentEnrollment;
use Timber\Timber;

/**
 * Class names can not start with a number so the 404 controller has a special cased name
 */
class Error404Controller extends Controller
{
    public function handle()
    {




        $context = Timber::get_context();
        $crs = Course::builder()->whereIdIn([8])->get()[0];

//        dd(StudentEnrollment::get_all_students_by_course($crs));
//        dd(StudentEnrollment::)
//        dd(StudentEnrollment::add_student_to_course(wp_get_current_user(), $crs, 10));
//        dd(StudentEnrollment::remove_student_from_course(wp_get_current_user(), $crs));
//        dd(StudentEnrollment::check_if_student_in_course(wp_get_current_user(), $crs));

//        dd(StudentEnrollment::get_student_association(wp_get_current_user()));

        $context["course"] = Course::query(['id' => 110])[0];
        $context["viewmodel"] = new CourseViewModel($crs);

        return new TimberResponse('courses/course.twig', $context);
    }
}
