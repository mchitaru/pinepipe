<?php

namespace App\Http\Controllers;

use App\Category;
use App\Article;
use App\Http\Requests\ArticleDestroyRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Requests\ArticleStoreRequest;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $path = $request['path'];

        $categories = explode('/', $path);
        $slug = array_pop($categories);

        $article = Article::where('created_by', \Auth::user()->creatorId())
                                ->where('slug', $slug)
                                ->first();

        if($article != null) {
            $path = implode('/', $categories);
            $slug = array_pop($categories);
        }


        $category = Category::where('created_by', \Auth::user()->creatorId())
                                ->where('slug', $slug)
                                ->first();

        return view('articles.create', compact('category', 'categories', 'path'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleStoreRequest $request)
    {
        $post = $request->validated();

        // $content = $post['content'];

        // dump($content);
        // $article = Article::make();

        // libxml_use_internal_errors(true);

        // $dom = new \domdocument();
        // $dom->loadHtml($request['content'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // $images = $dom->getElementsByTagName('img');

        // dump($images);
        
        // foreach ($images as $count => $image) {
            
        //    $src = $image->getAttribute('src');

        //    if (preg_match('/data:image/', $src)) {
        //        preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
        //        $mimeType = $groups['mime'];

        //        $avatar = $user->hasMedia('logos') ? $user->media('logos')->first()->getFullUrl() : null;    

        //        $file = $article->addMedia($request->file('file'))->toMediaCollection('articles', 'local');
       
        //        $path = '/images/' . uniqid('', true) . '.' . $mimeType;
               
        //        Storage::disk('s3')->put($path, file_get_contents($src));
        //        $image->removeAttribute('src');
        //        $image->setAttribute('src', Storage::disk('s3')->url($path));
        //    }
        // }

        // $post['content'] = $dom->savehtml();

        $article = Article::createArticle($post);

        $request->session()->flash('success', __('Article successfully created.'));

        $url = $post['path'].'/'.$article->slug;
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Article $article)
    {
        $path = $request['path'];

        $categories = explode('/', $path);
        $slug = array_pop($categories);

        $article = Article::where('created_by', \Auth::user()->creatorId())
                                ->where('slug', $slug)
                                ->first();

        if($article != null) {
            $path = implode('/', $categories);
            dump($path);
            $slug = array_pop($categories);            
        }

        $category = Category::where('created_by', \Auth::user()->creatorId())
                                ->where('slug', $slug)
                                ->first();

        return view('articles.edit', compact('article', 'category', 'categories', 'path'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        $post = $request->validated();

        $article->updateArticle($post);

        $request->session()->flash('success', __('Article successfully updated.'));

        $url = $post['path'].'/'.$article->slug;
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArticleDestroyRequest $request, Article $article)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        dump(URL::current());

        $article->delete();

        return Redirect::to(URL::previous())->with('success', __('Article successfully deleted.'));
    }
}
