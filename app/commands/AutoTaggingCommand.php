<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class AutoTaggingCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:auto_tagging_detect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $comics = Comic::all();
        $tags = ['hoge', 'fuga'];

        foreach ($comics as $comic) {
            DB::transaction(function() use($comic) {
                if (TagMap::whereHas('tag', function($q) { $q->where('name', 'like', 'hoge'); })->first()) {
                    return;
                }
                $tag = Tag::firstOrCreate(['name' => 'hoge']);
                $tag_map = TagMap::create(['comic_id' => $comic->id, 'tag_id' => $tag->id]);
            });
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
