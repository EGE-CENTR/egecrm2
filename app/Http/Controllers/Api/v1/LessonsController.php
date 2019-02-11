<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\Lesson, Lesson\ClientLesson, Teacher, Client\Client};
use App\Http\Resources\Lesson\{LessonResource, LessonCollection};

class LessonsController extends Controller
{
    protected $filters = [
        'equals' => ['group_id']
    ];

    public function index(Request $request)
    {
        $query = Lesson::orderBy('date', 'asc')->orderBy('time', 'asc');
        $this->filter($request, $query);
        return LessonCollection::collection($query->get());
    }

    public function store(Request $request)
    {
        return new LessonResource(Lesson::create($request->all()));
    }

    public function update(Request $request, $id)
    {
        $model = Lesson::find($id);
        $model->update($request->all());
        foreach($request->clientLessons as $clientLesson) {
            if (isset($clientLesson['id'])) {
                $client_lesson = ClientLesson::find($clientLesson['id']);
                if (isset($clientLesson['to_be_deleted'])) {
                    $client_lesson->delete();
                } else {
                    $client_lesson->update($clientLesson);
                }
            } else {
                ClientLesson::create(array_merge($model->toArray(), $clientLesson));
            }
        }
        return new LessonResource($model);
    }

    public function destroy($id)
    {
        Lesson::find($id)->delete();
    }
}
