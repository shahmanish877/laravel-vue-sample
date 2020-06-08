<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Question;

class AcceptAnswerController extends Controller
{
    public function __invoke(Answer $answer)
    {
        // For single method in a controller
        $this->authorize('accept',$answer);
        $answer->question->acceptBestAnswer($answer);

        return back();
    }
}
