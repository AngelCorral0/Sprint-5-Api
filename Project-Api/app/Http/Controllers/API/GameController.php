<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
       
    public function diceRoll($id)
    {  
       $authUser= Auth::user()->$id;

       if($authUser==$id)
       {        
        $dice1 = rand(1,6);
        $dice2 = rand(1,6);
        $total = $dice1+$dice2;
        $result = $total== 7? 'You Win!' : 'You Lost';

        $game = new Game();

        $game->user_id = $id;
        $game->dice1;
        $game->dice2;
        $game->total;
        $game->result;

        $game->save();

        

       }

       


    }

    
    public function show(string $id)
    {
        //
    }

   
    public function update(Request $request, string $id)
    {
        //
    }

   
    public function destroy(string $id)
    {
        //
    }
}
