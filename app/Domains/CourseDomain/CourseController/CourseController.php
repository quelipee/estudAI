<?php

namespace App\Domains\CourseDomain\CourseController;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\CourseDTO\TopicDTO;
use App\Domains\CourseDomain\CourseService\CourseService;
use App\Domains\CourseDomain\Interfaces\CourseTopicsContracts;
use App\Domains\CourseDomain\Requests\CourseRequest;
use App\Domains\CourseDomain\Requests\TopicRequest;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Topic;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use JetBrains\PhpStorm\NoReturn;

class CourseController extends Controller
{
    public function __construct(
        protected CourseTopicsContracts $courseTopicsService,
    ){}

    public function courses(): View|Factory|Application
    {
        $courses = $this->courseTopicsService->getAllCourses();

        return view('index',compact('courses'));
    }

    /**
     * @throws Exception
     */
    public function newCourses(CourseRequest $request): Application|Redirector|RedirectResponse
    {
        $this->courseTopicsService->
        addCourse(newCourseDTO::fromValidatedNewCourse($request));

        return redirect(route('index'),302);
    }

    public function deleteCourse(Course $course): Application|Redirector|RedirectResponse
    {
        $this->courseTopicsService
            ->destroyCourse($course);

        return redirect(route('index'),302);
    }

    public function updateCourse(CourseRequest $request, int $id) : Application|RedirectResponse|Redirector
    {
        $courseUpdate = $this->courseTopicsService
            ->updateCourse(newCourseDTO::fromValidatedNewCourse($request), $id);

        return redirect(
            route('index',[
                'course' => $courseUpdate
            ]),302);
    }

    public function insertTopicsInCourses(TopicRequest $request): Application|Redirector|RedirectResponse
    {
        $this->courseTopicsService->addTopic(TopicDTO::fromValidatedNewTopics($request));
        return redirect()->back()->with('status', 'Topic added successfully');
    }

    public function deleteTopicsInCourses(Topic $topic): Application|Redirector|RedirectResponse
    {
        $this->courseTopicsService->destroyTopic($topic);
        return redirect()->back()->with('status', 'Topic deleted');
    }

    public function updateTopicsInCourses(TopicRequest $request,Topic $topic): RedirectResponse
    {
        $this->courseTopicsService->updateTopic(TopicDTO::fromValidatedNewTopics($request),$topic);
        return redirect()->route('edit',$topic->course_id);
    }
}
