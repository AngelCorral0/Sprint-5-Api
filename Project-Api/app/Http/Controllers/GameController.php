<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class GameController extends Controller
{

    public function diceRoll($id)
    {
        $authUser = User::find(auth()->user()->id);
        
        if ($authUser->id == $id) {

            $dice1 = rand(1, 6);
            $dice2 = rand(1, 6);
            $total = $dice1 + $dice2;
            $result = $total == 7 ? 'You Win!' : 'You Lost';

            $authUser->total_rolls += 1;
            if ($result == 'You Win!') {
                $authUser->successful_rolls += 1;
            }
            $newPercentage = $authUser->successful_rolls / $authUser->total_rolls *100;
            $authUser->winning_percentage = $newPercentage;

            $authUser->save();


            $game = new Game();
            $game->user_id = $id;
            $game->dice1 = $dice1;
            $game->dice2 = $dice2;
            $game->total = $total;
            $game->result = $result;

            $game->save();

            return response($game, 200);
        } else {

            return response([
                'message' => 'Unregistered user'
            ]);
        }
    }

    
    public function userGames($id)
    {
        $authUser = auth()->user()->id;
        if ($authUser != $id) {
            return response(['message' => 'Access Denied'], 401);
        }

        $games = Game::where('user_id', $id)->get();

        if ($games->isEmpty()) {
            return response(['message' => 'No plays found for this user'], 401);
        }

        $wins = $games->where('result', 'You Win!')->count();
        $percentage = ($wins / $games->count()) * 100;

        return response([
            'plays' => $games,
            'percentage' => $percentage . '%',
        ], 200);
    }

    public function rankingAverage()
    {
        $authUser = auth()->user();
        $users = User::orderBy('winning_percentage', 'desc')->get();
        $usersRank = array();

        if ($authUser->role == 'admin') {
            return response($users, 201);
        } else {
            foreach ($users as $user) {
                $userB = [
                    'username' => $user->username,
                    'winning percentage' => $user->winning_percentage,
                    'total rolls' => $user->total_rolls,
                    'successful rolls' => $user->successful_rolls,
                ];
                array_push($usersRank, $userB);
            }
            return response($usersRank, 201);
        }
    }

    public function winner()
    {
        if (auth()->user()->role != 'admin') {
            return response([
                'message' => 'Unauthorized'
            ]);
        } else {
            $loser = User::orderBy('winning_percentage', 'desc')->first();
            $loserClean = [
                'username' => $loser->username,
                'winning percentage' => $loser->winning_percentage,
                'total rolls' => $loser->total_rolls,
                'successful rolls' => $loser->successful_rolls,
            ];
        }
        return response($loserClean, 201);
    }


    public function loser()
    {
        if (auth()->user()->role != 'admin') {
            return response([
                'message' => 'Unauthorized'
            ]);
        } else {
            $loser = User::orderBy('winning_percentage', 'asc')->first();
            $loserClean = [
                'username' => $loser->username,
                'winning percentage' => $loser->winning_percentage,
                'total rolls' => $loser->total_rolls,
                'successful rolls' => $loser->successful_rolls,
            ];
        }
        return response($loserClean, 201);
    }

    public function destroy(string $id)
    {
        $authUser = User::find(auth()->user()->id);

        if (!User::find($id)) {
            return response(['message' => 'User not found']);
        } elseif ($authUser->id == $id) {
            $userPlays = Game::where('user_id', $id)->first('id');

            if ($userPlays !== null) {
                Game::where('user_id', $id)->delete();
                
                $authUser->winning_percentage = 0.00;
                $authUser->successful_rolls = 0;
                $authUser->total_rolls = 0;
                $authUser->save();

                return response(['message' => 'All your plays have been successfully removed']);
            } else {
                return response(['message' => "You don't have any plays to remove"]);
            }
        } else {
            return response(['message' => 'Unauthorized'], 401);
        }
    }
}
