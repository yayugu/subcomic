<?php

class HomeController extends BaseController
{
    public function index()
    {
        $comics = Comic::paginate(200);
        $comics->setBaseUrl(action('comicIndex'));
        return View::make('home.index')
            ->with('comics', $comics);
    }
}