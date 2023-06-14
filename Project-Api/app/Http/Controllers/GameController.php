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
        $authUser = auth()->user()->id;

        if ($authUser == $id) {
           
            $dice1 = rand(1, 6);
            $dice2 = rand(1, 6);
            $total = $dice1 + $dice2;
            $result = $total == 7 ? 'You Win!' : 'You Lost';

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

    public function userRanking()
    {
        if (auth()->user()->role != 'admin') {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        } else {
            $ranking = User::leftJoin('games', 'users.id', '=', 'games.user_id')
                ->select('users.id', 'users.username', DB::raw('SUM(games.total) as total_score'))
                ->groupBy('users.id', 'users.username')
                ->orderByDesc('total_score')
                ->get();

            return response()->json($ranking, 200);
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
        if (auth()->user()->role != 'admin') {
            return response(['message' => 'Unauthorized'], 401);
        } else {
            $totalPlays = Game::count();
            $totalSuccess = Game::where('result', 'You Win!')->count();

            if ($totalPlays == 0) {
                return response()->json(['message' => 'There are no plays']);
            }

            $average_ranking = ($totalSuccess / $totalPlays) * 100;
        }

        return response()->json([
            'Average ranking of all users: ' => round($average_ranking, 2) . '%'
        ]);
    }

    public function winner()
    {
        if (auth()->user()->role != 'admin') {
            return response([
                'message' => 'Unauthorized'
            ]);
        } else {
            $winner = User::leftJoin('games', 'users.id', '=', 'games.user_id')
                ->select('users.id', 'users.username', DB::raw('SUM(games.total) as total_score'))
                ->groupBy('users.id', 'users.username')
                ->orderByDesc('total_score')
                ->limit(1)
                ->get();

            return response()->json([
                'The winner is ' => $winner,
            ]);
        }
    }
    public function loser()
    {
        if (auth()->user()->role != 'admin') {
            return response([
                'message' => 'Unauthorized'
            ]);
        } else {
            $loser = User::leftJoin('games', 'users.id', '=', 'games.user_id')
                ->select('users.id', 'users.username', DB::raw('SUM(games.total) as total_score'))
                ->groupBy('users.id', 'users.username')
                ->orderBy('total_score')
                ->limit(1)
                ->get();

            return response()->json([
                'The loser is ' => $loser,
            ]);
        }
    }

    public function destroy(string $id)
    {
        $authUser = auth()->user()->id;

        if (!User::find($id)) {
            return response(['message' => 'User not found']);
        } elseif ($authUser == $id) {
            $userPlays = Game::where('user_id', $id)->first('id');

            if ($userPlays !== null) {
                Game::where('user_id', $id)->delete();
                return response(['message' => 'All your plays have been successfully removed']);
            } else {
                return response(['message' => "You don't have any plays to remove"]);
            }
        } else {
            return response(['message' => 'Unauthorized'], 401);
        }
    }
}
