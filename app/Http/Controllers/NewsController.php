<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class NewsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function getNews()
    {
        $data = request()->validate([
            'country' => 'required|exists:countries,code',
            'category' => 'required|exists:categories,name',
        ]);
        $country = Country::whereCode($data['country'])->first();
        $category = Category::whereName($data['category'])->first();
        $articles = $country->articles()->whereCategoryId($category->id)->paginate(10);
        return response()->json($articles);
    }
    public function getCategories()
    {
        $categories = Category::select('name')->get()->pluck('name');
        return response()->json($categories);
    }
    public function getCountries()
    {
        $countries = Country::select('code')->get()->pluck('code');
        return response()->json($countries);
    }
}
