<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question){
        $question->favourites()->attach(Auth::id());
        return back();
    }

    public function destroy(Question $question){
        $question->favourites()->detach(Auth::id());
        return back();
    }
}
