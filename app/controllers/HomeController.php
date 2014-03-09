<?php

class HomeController extends BaseController {

	public function index()
	{
		return View::make('home.index')
            ->with('archiveFiles', ArchiveFile::all());
	}

}