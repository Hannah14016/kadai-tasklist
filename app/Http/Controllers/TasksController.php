<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            $data += $this->counts($user);
            return view('tasks.index', $data);
        }else {
            return view('welcome');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()){
            $user = \Auth::user();
            $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
        } else {
            return view('welcome');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:191',
        ]);
        
        $request->user()->tasks()->create([
            'status' => $request->content,
            'content' => $request->content, 
        ]);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = \App\Task::find($id);
        $user = \Auth::user();
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
        
            if (\Auth::id() === $task->user_id){
            
                return view('tasks.show', [
                    'user' => $user,
                    'task' => $task,
                    'tasks' => $tasks,
                ]);
            } else {
                return redirect('/');
            }
        } 
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()){
            $user = \Auth::user();
            $task = Task::find($id);

            return view('tasks.edit', [
            'task' => $task,
        ]);
        } else {
            return view('welcome');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        
        $this->validate($request, [
            'status' => 'required|max:10',   // add
            'content' => 'required|max:191',
        ]);
        
        $task = Task::find($id);
        if (\Auth::id() === $task->user_id) {
            $task->status = $request->status; 
            $task->content = $request->content;
            $task->save();
        }
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = \App\Task::find($id);

        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
            return redirect('/');
    }
}
