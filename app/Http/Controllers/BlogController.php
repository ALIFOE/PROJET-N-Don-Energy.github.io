<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * Affiche la liste des articles
     */
    public function index()
    {
        $articles = Article::with(['category', 'author', 'tags'])
            ->where('published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $categories = Category::all();
        
        return view('blog', compact('articles', 'categories'));
    }

    /**
     * Affiche un article individuel
     */
    public function show($slug)
    {
        $article = Article::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        // Récupérer les articles similaires (même catégorie)
        $relatedArticles = Article::with(['category', 'author'])
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('published', true)
            ->orderBy('published_at', 'desc')
            ->take(2)
            ->get();

        return view('blog.show', compact('article', 'relatedArticles'));
    }

    /**
     * Recherche d'articles
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $articles = Article::with(['category', 'author', 'tags'])
            ->where('published', true)
            ->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $categories = Category::all();
        
        return view('blog', compact('articles', 'categories', 'search'));
    }

    /**
     * Affiche les articles d'une catégorie
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $articles = Article::with(['category', 'author', 'tags'])
            ->where('category_id', $category->id)
            ->where('published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $categories = Category::all();
        
        return view('blog', compact('articles', 'categories', 'category'));
    }

    /**
     * Affiche les articles avec un tag spécifique
     */
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $articles = $tag->articles()
            ->with(['category', 'author', 'tags'])
            ->where('published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $categories = Category::all();
        
        return view('blog', compact('articles', 'categories', 'tag'));
    }

    /**
     * Affiche les articles d'un auteur
     */
    public function author($id)
    {
        $articles = Article::with(['category', 'author', 'tags'])
            ->where('author_id', $id)
            ->where('published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $categories = Category::all();
        
        return view('blog', compact('articles', 'categories'));
    }
} 