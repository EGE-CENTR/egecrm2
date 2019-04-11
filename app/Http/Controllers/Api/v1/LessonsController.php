<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Lesson\Lesson,
    Lesson\ClientLesson,
    Teacher,
    Client\Client,
    Group\Group,
    Contract\Contract
};
use App\Http\Resources\Lesson\{LessonResource, LessonCollection};
use User;

class LessonsController extends Controller
{
    protected $filters = [
        'equals' => ['group_id', 'status', 'teacher_id']
    ];

    public function index(Request $request)
    {
        $query = Lesson::orderBy('date', 'asc')->orderBy('time', 'asc');
        $this->filter($request, $query);
        return LessonCollection::collection($query->get());
    }

    public function store(Request $request)
    {
        $item = new Lesson($request->all());
        $item->save();
        return new LessonResource($item);
    }

    public function show($id)
    {
        return new LessonResource(Lesson::find($id));
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
                ClientLesson::create(array_merge($clientLesson, ['id' => $id]));
            }
        }
        return new LessonResource($model);
    }

    public function destroy($id)
    {
        Lesson::find($id)->delete();
    }

    /**
     * Провести занятие
     */
    public function conduct($id, Request $request)
    {
        $lesson = Lesson::find($id);
        $lesson->status = 'conducted';
        $lesson->conducted_at = now()->format(DATE_TIME_FORMAT);
        $lesson->conducted_email_id = User::emailId();
        $lesson->topic = $request->topic;
        $lesson->save();

        foreach ($request->clients as $c) {
            $client = Client::find($c['id']);

            // Получаем $price для текущего ученика
            // Находим активный договор по данному учебному году, по данному классу
            // Получаем общее кол-во предметов, получаем сумму договора, умножаем всё на скидку
            $lastContract = $client
                ->contracts()
                ->active()
                ->where('year', $lesson->group->year)
                ->where('grade_id', $lesson->group->grade_id)
                ->first();

            // А что если догавар не нашелся?
            if ($lastContract === null) {
                $grade_id = null;
                $price = null;
            } else {
                $price = $lastContract->sum / $lastContract->subjects()->sum('lessons');
                if ($lastContract->discount > 0) {
                    $price = (100 - $lastContract->discount) / 100 * $price;
                }
                $grade_id = $lastContract->grade_id;
            }

            ClientLesson::create([
                'client_id' => $c['id'],
                'lesson_id' => $id,
                'grade_id' => $grade_id,
                'price' => $price,
                'is_absent' => isset($c['is_absent']) && $c['is_absent'] === true,
                'late' => isset($c['late']) ? $c['late'] : null,
                'comment' => isset($c['comment']) ? $c['comment'] : '',
            ]);
        }

        Lesson::whereId($lesson->id)->update([
            'bonus' => $lesson->calculateBonus(),
        ]);

        return new LessonResource($lesson->fresh());
    }
}
