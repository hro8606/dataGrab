<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{

    function getRandomStr($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        $articles = Article::all();

        return view('admin.admin',compact('articles'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        return view('admin.edit',compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'dateTime' => 'required',
            'articleUrl' => 'required',
            'img' => 'mimes:jpeg,bmp,png',
        ]);


        $article = Article::findOrFail($id);
//        $article->update($request->all());

        $article->title = $request->title;
        $article->description = $request->description;
        $article->cr_date = $request->dateTime;
        $article->article_url = $request->articleUrl;

        if(isset($request->img)){

            unlink(public_path().'/uploads/images/'.$article->main_image);

            $image = $request->file('img');
            $imageExt = $image->getClientOriginalExtension();

            $contents = file_get_contents($image);
            $filename = $name = time() . $this->getRandomStr(). '.' . $imageExt;

            Storage::disk('uploads')->put('images/'.$filename, $contents, 'public');

            $article->main_image = $filename;
        }

        $article->save();

        return redirect('/admin');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        unlink(public_path().'/uploads/images/'.$article->main_image);
        return redirect('/admin');
    }
}








