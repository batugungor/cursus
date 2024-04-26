<?php

namespace app\ViewModels;

use app\PostTypes\Course;

class CourseViewModel
{
    public Course $course;
    public array $structure = [];
    public array $association = [];
    public function __construct($course)
    {
        $this->course = $course;
        $this->association = carbon_get_post_meta($this->course->id, 'structure');
//        dd(var_dump($association));

        foreach ($this->association as $item) {
            $post = get_post($item["id"]);

            $array = [
                'id' => $item["id"],
                'object' => $post,
                'type' => $item["subtype"]
            ];

            if($item["subtype"] === "quiz") {
                $array["questions"] = carbon_get_post_meta($post->ID, "questions");
//                dd($array["questions"]);
//                dd($array);

            }


            $this->structure[] = $array;
        }
    }

    public function getStructure() {
        return $this->structure;
    }

    public function getAssociation($return = []) {
        foreach ($this->association as $item) {
            $return[] = get_post($item["id"]);
        }

        return $return;
    }

    public function getCourse() {
        return $this->course;
    }
}
