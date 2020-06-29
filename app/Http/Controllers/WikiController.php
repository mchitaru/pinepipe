<?php

namespace App\Http\Controllers;

use App\Category;
use App\Article;
use App\User;

use Illuminate\Http\Request;

class WikiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user, $categories = null)
    {
        if (!$request->ajax())
        {
            return view('wiki.page');        
        }

        if(\Auth::user() && (\Auth::user()->creatorId() == $user->id)){

            $published = [0, 1];
        }else{

            $published = [1];
        }

        $categories = explode('/', $categories);
        $slug = array_pop($categories);

        $article = Article::where('created_by', $user->id)
                                ->where('slug', $slug)
                                ->first();

        if($article != null) {
            $slug = array_pop($categories);
        }

        $category = Category::where('created_by', $user->id)
                                ->where('slug', $slug)
                                ->first();        
        
        $articles = Article::where('created_by', $user->id)
                            ->where(function ($query) use ($request, $category) {

                                if(isset($request['filter'])){

                                    $query->where('title','like','%'.$request['filter'].'%');
                                }else{
                                    
                                    $query->where('category_id', $category ? $category->id : null);
                                }
                            })
                            ->whereIn('published', $published)
                            ->paginate(25, ['*'], 'article-page');    

        if(!isset($request['filter'])){

            $categories = Category::where('created_by', $user->id)
                                    ->where('class', Article::class)
                                    ->where('category_id', $category ? $category->id : null)
                                    ->get();
        }else{

            $categories = collect();
        }

        $home = route('wiki.index', $user);

        return view('wiki.index', compact('home', 'user', 'category', 'article', 'categories', 'articles'))->render();
    }
}
