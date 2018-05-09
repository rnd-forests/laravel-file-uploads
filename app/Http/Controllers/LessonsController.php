<?php

namespace App\Http\Controllers;

use App\Components\Form;
use App\Components\Grid;
use App\Http\Requests\Lesson\Store as StoreRequest;
use App\Lesson;
use Illuminate\Support\Arr;

class LessonsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $form = new Form(['url' => $this->action('store')]);

        $lessons = (new Grid(['controller' => $this]))
            ->query(Lesson::latest())
            ->paginate();

        return view('lessons.index', compact('form', 'lessons'));
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $lesson = Lesson::create(Arr::except($data, 'audio'));

        resolve('upload')
            ->handler('lesson-audio')
            ->withFile(Arr::get($data, 'audio'))
            ->withUser($request->user())
            ->withLesson($lesson)
            ->store();

        flash('Lesson has been stored.');

        return back();
    }
}
