<?php

namespace App\Http\Controllers;

use App\Board;
use App\Libs\Response;
use App\User;
use Validator;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('APIToken');
        $this->token = request('token');
        $this->userId = \App\LoginToken::whereToken($this->token)->first()['user_id'];
    }

    public function index()
    {

        return Board::whereHas('members', function($members){
            $members->whereUserId($this->userId);
        })->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'Invalid Field']);
        }

        $board = Board::create([
            'creator_id' => $this->userId,
            'name' => $request->name
        ]);

        $board->members()->attach($this->userId);

        return Response::success(['message' => 'Create Board Success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        $check = Board::findOrfail($board->id);

        if($check->members()->whereUserId($this->userId)->first()){
            return Board::with(['members', 'lists' => function($lists){
                $lists->with(['cards' => function($cards){
                    $cards->orderBy('order', 'asc')->get();
                }])->orderBy('order', 'asc')->get();
            }])->findOrFail($board->id);
        }

        return Response::invalid(['msg' => 'Unauthorized User']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'Invalid Field']);
        }

        $data = Board::findOrFail($board->id);

        if($data->members()->whereUserId($this->userId)->first()){
            $data->update([
                'name' => $request->name
            ]);

            return Response::success(['message' => 'Update Board Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        $data = Board::findOrFail($board->id);

        if($data->creator_id == $this->userId){
            $data->forceDelete();
            $data->delete();

            return Response::success(['message' => 'Delete Board Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    public function addMember($board_id, Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'exists:users,username'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'User did not exist']);
        }

        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $user = User::whereUsername($request->username)->first();

            $board->members()->attach($user->id);

            return Response::success(['message' => 'Add Member Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    public function removeMember($board_id, $user_id){
        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $user = User::findOrFail($user_id);

            $board->members()->detach($user->id);

            return Response::success(['message' => 'Remove Member Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);

    }
}
