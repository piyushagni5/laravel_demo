<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Task\StoreRequest;
use DB;
use App\Models\Task;
use App\Http\Requests\Task\UpdateRequest;
use Illuminate\Http\RedirectResponse;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('is_completed')
            ->orderBy('id')
            ->get();

        return view('task.index')->with(compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $task = DB::transaction(fn() => Task::create($request->validated()));

        return to_route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('task.show')->with(compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('task.edit')->with(compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Task $task)
    {
        DB::transaction(fn() => $task->update($request->validated()));

        return to_route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        DB::transaction(fn() => $task->delete());

        return to_route('tasks.index');
    }

    public function complete(Task $task): RedirectResponse
    {
        DB::transaction(fn() => $task->update(['is_completed' => true]));
        // dd($task);

        return to_route('tasks.index');
    }

    public function yetComplete(Task $task): RedirectResponse
    {
        DB::transaction(fn() => $task->update(['is_completed' => false]));

        return to_route('tasks.index');
    }
}
