<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function favourites()
    {
        return $this->belongsToMany(Question::class, 'favourites_question');
    }

    public function VoteQuestions()
    {
        return $this->morphedByMany(Question::class, 'votable');
    }

    public function VoteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'votable');
    }

    public function getUrlAttribute()
    {
        // return route("questions.show", $this->id);
        return '#';
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getAvatarAttribute()
    {
        $email = $this->email;
        $size = 32;

        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;
    }

    public function votingQuestion(Question $question, $vote)
    {
        $voteQuestions = $this->VoteQuestions();

        if ($voteQuestions->where('votable_id', $question->id)->exists()) {
            $voteQuestions->updateExistingPivot($question, ['vote' => $vote]);
        } else {
            $voteQuestions->attach($question, ['vote' => $vote]);
        }

        //update vote_count column in question
        $question->load('votes');
        $downVotes = (int)$question->downVotes()->sum('vote');
        $upVotes = (int)$question->upVotes()->sum('vote');

        $question->votes_count = $upVotes + $downVotes;
        $question->save();
    }

    public function votingAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->VoteAnswers();

        if ($voteAnswers->where('votable_id', $answer->id)->exists()) {
            $voteAnswers->updateExistingPivot($answer, ['vote' => $vote]);
        } else {
            $voteAnswers->attach($answer, ['vote' => $vote]);
        }

        //update vote_count column in question
        $answer->load('votes');
        $downVotes = (int)$answer->downVotes()->sum('vote');
        $upVotes = (int)$answer->upVotes()->sum('vote');

        $answer->votes_count = $upVotes + $downVotes;
        $answer->save();
    }

}
