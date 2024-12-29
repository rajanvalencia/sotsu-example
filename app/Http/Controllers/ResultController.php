<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function getResults(string $category_id)
    {
        $user = Auth::user();

        $category = Category::find($category_id);

        if (! $category) {
            abort(404, 'Category not found');
        }

        $result = $category->getNewestResultByUserIdAndCategoryId($user->id, $category_id);

        $results = $category->getResultsByUserIdAndCategoryId($user->id, $category_id);

        /**
         * Chart.js Bar Chart Data
         * https://www.chartjs.org/docs/latest/getting-started/
         */
        $graph_data = [
            'labels' => $results->map(function ($result) {
                return $result['datetime'];
            })->toArray(),
            'datasets' => [
                [
                    'label' => '正答率',
                    'data' => $results->map(function ($result) {
                        return $result['correct_answer_rate'];
                    })->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        return view('home.results', compact('user', 'category', 'result', 'graph_data'));
    }
}
