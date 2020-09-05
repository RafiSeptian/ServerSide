<?php

namespace App\Http\Controllers;

use App\BoardList;
use App\Board;
use App\Libs\Response;
use Validator;
use Illuminate\Http\Request;

class BoardListController extends Controller
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
        //
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
    public function store($board_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'Invalid Field']);
        }

        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $findOrder = BoardList::orderBy('order', 'asc')->first();

            if($findOrder){
                $order = $findOrder->order + 1;
            }
            else{
                $order = 1;
            }

            BoardList::create([
                'board_id' => $board_id,
                'name' => $request->name,
                'order' => $order
            ]);

            return Response::success(['message' => 'Add List Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function show(BoardList $boardList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function edit(BoardList $boardList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function update($board_id, $list_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'Invalid Field']);
        }

        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $data = BoardList::findOrFail($list_id);

            $data->update([
                'name' => $request->name
            ]);

            return Response::success(['message' => 'Update List Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BoardList  $boardList
     * @return \Illuminate\Http\Response
     */
    public function destroy($board_id, $list_id)
    {
        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $data = BoardList::findOrFail($list_id);
            $data->forceDelete();
            $data->delete();

            return Response::success(['message' => 'Delete List Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    public function moveRight($board_id, $list_id){
        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){

            $mylist = BoardList::findOrFail($list_id);

            $before = BoardList::where('board_id', $board_id)->where('order', '>', $mylist->order)->first();

            if($before){
                $mylist->update([
                    'order' => $before->order
                ]);

                $before->update([
                    'order' => $before->order - 1
                ]);
                return Response::success(['message' => 'Move Success']);
            }
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    public function moveLeft($board_id, $list_id){
        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){

            $mylist = BoardList::findOrFail($list_id);

            $before = BoardList::whereBoardId($board_id)->where('order', '<', $mylist->order)->first();

            if($before){
                $mylist->update([
                    'order' => $before->order
                ]);

                $before->update([
                    'order' => $before->order + 1
                ]);
                return Response::success(['message' => 'Move Success']);
            }
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }
}
