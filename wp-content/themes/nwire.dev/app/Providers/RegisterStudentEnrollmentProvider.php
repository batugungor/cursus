<?php

namespace app\Providers;

use app\Helpers\StudentEnrollment;
use app\PostTypes\Course;
use app\Tables\DataTableCourseOverview;
use app\Tables\DataTable;
use app\Tables\DataTableUsers;
use app\ViewModels\CourseViewModel;
use Rareloop\Lumberjack\Config;
use Timber\Post;
use WP_Query;

class RegisterStudentEnrollmentProvider
{
    private string $page_slug = 'custom-list-table';
    private string $action = "show-all-users";
    private array $users = [];
    private CourseViewModel $currentCourse;

    public function boot(Config $config): void
    {
        add_action('admin_menu', function () use ($config) {
            add_submenu_page(
                'non-existing-parent-menu',
                'Toegangbeheer',
                'Custom List Table',
                'manage_options',
                $this->page_slug,
                function () use ($config) {
                    $this->currentCourse = new CourseViewModel(
                        Course::builder()->whereIdIn([$_GET["post"]])->get()[0]
                    );

                    $users = new DataTableUsers(get_users());

                    if (!isset($_POST["action"])) {
                        $this->render_table("Lijst van gebruikers", $users);
                    }

                    $this->handle_post_actions();
                }
            );
        });

        add_action('admin_menu', function () use ($config) {
            add_submenu_page(
                'non-existing-parent-menu',
                'Toegangbeheer',
                'Custom List Table',
                'manage_options',
                'enrollment',
                function () use ($config) {
                    $course = new Course($_GET["post"]);
                    $this->currentCourse = new CourseViewModel(
                        $course
                    );

                    $users = new DataTableUsers(StudentEnrollment::get_all_students_by_course($course));

                    if (!isset($_POST["action"])) {
                        $this->render_table("Lijst van studenten", $users);
                    }

                    $this->handle_post_actions();
                }
            );
        });
    }

    public function handle_post_actions($extra = false): void
    {
        if (isset($_POST["action"])) {
            if ($_POST["action"] === "edit_access_one_by_one") {
                if (!$extra) {
                    $user = $_POST["item"][0];
                    $this->users = $_POST["item"];
                } else {
                    $user = $_POST["users"][0];
                    $this->users = $_POST["users"];
                }


                $user = get_user_by('ID', $user);

                $courses = new DataTableCourseOverview($this->generate_list_of_course_association($user->ID));
                $this->render_table("Toegangbeheer voor <strong>" . $user->first_name . ' ' . $user->last_name . '</strong>', $courses);
            }

            if ($_POST["action"] === "save_access") {
                if (isset($_POST["users"]) && !empty($_POST["users"])) {
                    $user = get_user_by('ID', intval($_POST["users"][0]));
                    StudentEnrollment::remove_student_from_course($user, $this->currentCourse->course);

                    if (isset($_POST["item"])) {
                        foreach ($_POST["item"] as $item) {
                            StudentEnrollment::add_student_to_course($user, $this->currentCourse->course, $item);
                        }
                    } else {
                        StudentEnrollment::add_student_to_course($user, $this->currentCourse->course);
                    }

                    array_shift($_POST["users"]);

                    if (!empty($_POST["users"])) {
                        $_POST["action"] = "edit_access_one_by_one";
                        $this->users = $_POST["users"];

                        $this->handle_post_actions(true);
                    } else {
                        echo '<p>Voltooid.</p>';
                    }
                }
            }

            if ($_POST["action"] === "remove_access") {
                foreach ($_POST["item"] as $user) {
                    StudentEnrollment::remove_student_from_course(get_user_by('ID', $user), new Course($_REQUEST["post"]));
                }

                echo '<p>Voltooid.</p>';
            }

            if ($_POST["action"] === "give_all_basic_access") {
                foreach ($_POST["item"] as $user) {
                    StudentEnrollment::add_student_to_course(get_user_by('ID', $user), new Course($_REQUEST["post"]));
                }

                echo '<p>Voltooid.</p>';
            }
        }
    }


    public function generate_list_of_course_association($user): array
    {
        $items = [];
        foreach ($this->currentCourse->getAssociation() as $assocation) {
            $lessons = get_user_meta($user, 'lesson_access', true); // Get current lesson access

            if (isset($lessons[$this->currentCourse->course->ID])) {
                $has_access = (is_array($lessons[$this->currentCourse->course->ID]) && in_array($assocation->ID, $lessons[$this->currentCourse->course->ID]));
            } else {
                $has_access = false;
            }

            $items[] = [
                "ID" => $assocation->ID,
                "post_title" => $assocation->post_title,
                "type" => $assocation->post_type,
                "has_access" => $has_access
            ];

        }

        return $items;
    }

    public function render_table($title, $dataTable): void
    {
        ?>
        <div class="wrap">
            <h1><?php echo $title; ?></h1>
            <h3>Voor de lessenreeks: <?php echo $this->currentCourse->course->post_title ?></h3>
            <form method="post">
                <?php
                if (isset($_POST["action"])) {
                    if ($_POST["action"] == "edit_access_one_by_one" || $_POST["action"] == "save_access") {
                        foreach ($this->users as $user) {
                            ?>
                            <input name="users[]" type="hidden" value="<?php echo $user ?>">
                            <?php
                        }
                    }
                }
                ?>
                <?php $dataTable->display(); ?>
                <!--                <input type="submit" name="save_changes" class="button button-primary" value="Save Changes">-->
            </form>
        </div>
        <?php
    }
}
