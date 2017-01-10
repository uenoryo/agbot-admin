<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecture;
use App\Models\Attendance;
use App\Http\Requests\LectureStoreRequest;

class LectureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reset($id)
    {
        Attendance::where('lecture_id',  $id)
            ->update(['is_active' => false]);
        return redirect()->route('lecture.show',  $id);
    }

    public function show($id)
    {
        return view('lecture.show')->with([
            'lecture' => Lecture::find($id),
            'attendances' => Attendance::whereLectureId($id)
                ->whereIsActive(true)->get(),
        ]);
    }

    public function create()
    {
        return view('lecture.create')
            ->with([
                'lecture' => new Lecture,
            ]);
    }

    public function store(LectureStoreRequest $request)
    {
        $input = $request->only(['name']);
        $input['user_id'] = \Auth::user()->id;
        Lecture::create($input);
        return redirect('/');
    }
}
