<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        $articles = Article::all();
        return view('welcome',compact('articles'));
    }
}
