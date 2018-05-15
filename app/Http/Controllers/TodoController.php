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
        //$todo=[];
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
    
    public function update(Request $request) 
    {
    	//
    }

    public function delete(Request $request)
    {

    }
}
