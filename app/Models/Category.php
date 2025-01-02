<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Category extends Model
{
    protected $fillable = [
        'category_name',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answerSessions()
    {
        return $this->hasMany(AnswerSession::class);
    }

    public function getNewestResultByUserIdAndCategoryId(string $user_id, string $category_id)
    {
        $answerSession = AnswerSession::where(['user_id' => $user_id, 'category_id' => $category_id])
            ->orderBy('created_at', 'desc')
            ->first();

        // Check if an AnswerSession exists
        if (! $answerSession) {
            return null; // Or return an empty array if you prefer
        }

        $answers = Answer::where('answer_session_id', $answerSession->id)->get();

        $totalAnswers = $answers->count();
        $totalCorrectAnswers = $answers->where('is_correct', true)->count();

        return [
            'category_name' => $answerSession->category->category_name,
            'total_answers' => $totalAnswers,
            'total_correct_answers' => $totalCorrectAnswers,
            'correct_answer_rate' => $totalAnswers > 0
                ? round(($totalCorrectAnswers / $totalAnswers) * 100, 0)
                : 0,
            'datetime' => Carbon::parse($answerSession->created_at)
                ->timezone('Asia/Tokyo')
                ->format('Y-m-d H:i:s'),
        ];
    }

    public function getResultsByUserIdAndCategoryId(string $user_id, string $category_id)
    {
        return AnswerSession::where(['user_id' => $user_id, 'category_id' => $category_id])
            ->get()
            ->map(function ($answerSession) {
                $answers = Answer::where('answer_session_id', $answerSession->id)->get();

                $totalAnswers = 0;
                $totalCorrectAnswers = 0;
                foreach ($answers as $answer) {
                    if ($answer->is_correct) {
                        $totalCorrectAnswers++;
                    }
                    $totalAnswers++;
                }

                return [
                    'category_name' => $answerSession->category->category_name,
                    'total_answers' => $totalAnswers,
                    'total_correct_answers' => $totalCorrectAnswers,
                    'correct_answer_rate' => round(($totalCorrectAnswers / $totalAnswers) * 100, 0),
                    'datetime' => Carbon::parse($answerSession->created_at)
                        ->timezone('Asia/Tokyo')->format('Y-m-d H:i:s'),
                ];
            });
    }
}
