<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['body', 'user_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });

        static::deleted(function ($answer) {
            $answer->question->decrement('answers_count');
        });
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        //return $this->id === $this->question->best_answer_id ? 'vote-accepted' : '';

        return $this->isbest() ? 'vote-accepted' : '';
    }

    public function getIsBestAttribute(){
//        if ($this->isbest())
//            return 1;
//        else
//            return 0;
        return $this->isbest();
    }

    public  function isbest(){
        return $this->question->best_answer_id === $this->id;
    }
}
