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
            'search' => 'nullable|string',
        ]);
        $country = Country::whereCode($data['country'])->first();
        $category = Category::whereName($data['category'])->first();
        $query = $country->articles();
        if (isset($data['search'])) {
            $query = $query->where(function ($query) use ($data) {
                $query->where('title', 'like', '%' . $data['search'] . '%')
                    ->orWhere('content', 'like', '%' . $data['search'] . '%');
            });
        }
        $articles = $query->whereCategoryId($category->id)->paginate(10);
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
