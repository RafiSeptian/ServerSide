<?php

namespace App\Http\Controllers;

use App\Card;
use App\Board;
use App\Libs\Response;
use Validator;
use Illuminate\Http\Request;

class CardController extends Controller
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
    public function store($board_id, $list_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'Invalid Field']);
        }

        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $findOrder = Card::orderBy('order', 'asc')->first();

            if($findOrder){
                $order = $findOrder->order + 1;
            }
            else{
                $order = 1;
            }

            Card::create([
                'list_id' => $list_id,
                'task' => $request->task,
                'order' => $order
            ]);

            return Response::success(['message' => 'Add Card Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update($board_id, $list_id, $card_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'Invalid Field']);
        }

        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $data = Card::findOrFail($card_id);

            $data->update([
                'task' => $request->task
            ]);

            return Response::success(['message' => 'Update Card Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy($board_id ,$list_id, $card_id)
    {
        $board = Board::findOrFail($board_id);

        $check = $board->members()->whereUserId($this->userId)->first();

        if($check){
            $data = Card::findOrFail($card_id);
            $data->forceDelete();
            $data->delete();

            return Response::success(['message' => 'Delete Card Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }

    public function moveUp(){
        //
    }

    public function moveDown(){
        //
    }
}
