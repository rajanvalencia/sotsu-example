<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        $categories = Category::all();

        $graph_datas = [];
        foreach ($categories as $category) {
            $results = $category->getResultsByUserIdAndCategoryId($user->id, $category->id);

            $graph_datas[] = [
                'category_id' => $category->id,
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
        }

        // Return the 'home' view with the authenticated user and categories
        return view('home.index', compact('user', 'categories', 'graph_datas'));
    }
}
