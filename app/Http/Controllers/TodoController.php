<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TodoController extends BaseController
{
	private $user;
    private $todos;

	public function __construct(Request $request)
	{
		$this->user = $request->auth;
        $this->todos = Todo::orderBy('id', 'DESC')
                        ->where('user_id', $this->user->id)
                        ->get();
	}

    public function index(Request $request)
    {
    	return response()->json([
    		'data' => $this->todos
    	]);
    }

    public function showBy($filter)
    {
        
        switch ($filter) {

            case 'completed':
                $this->todos = $this->todos->where('complete', 1);
                break;
            
            case 'incomplete':
                $this->todos = $this->todos->where('complete', 0);
                break;

            default:
                $todos = Todo::orderBy('id', 'DESC')
                        ->where('user_id', $this->user->id);
                break;
        }


        return response()->json($this->todos);
    }

   

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'body' => 'required'
    	]);

    	$todo = Todo::create([
    		'body' => $request->body,
    		'user_id' => $this->user->id,
    	]);

    	return response()->json([
    		'data' => [
    			$todo
    		]
    	]);
    }

    public function edit()
    {

    }

    public function setComplete($id) 
    {
        $resp = false;
        $todo = Todo::find($id);
        if($todo->exists) {
            if($todo->complete == 0) {
                $todo->complete = 1;    
            } else {
                $todo->complete = 0;
            }
            
            $todo->save();

            $resp = true;
        }
        return response()->json([
            'data' => [
                $todo
            ]
        ]);
    }
    
    public function update(Request $request, $id) 
    {
    	$todo = Todo::find($id);

        if($todo->exists) {
            $todo->update($request->all());
            $todo->save();

            return response()->json([
                'data' => [
                    $todo
                ]
            ]);
        } else {
            return response()->json([
                'data' => [
                    'error' => 'Model not found'
                ]
            ]);
        }
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);
        if(!is_null($todo)) {
            $todo->delete();
            return response(null, Response::HTTP_NO_CONTENT);    
        } else {
            return response()->json([
                'data' => [

                    'error' => 'Model not found'
                ]
            ]);
        }
        
    }
}
