<?php

use Illuminate\Console\Command;
use Subcomic\TagDetector;
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

        foreach ($comics as $comic) {
            $tag_names = TagDetector::detect($comic->path);
            foreach ($tag_names as $tag_name) {
                if (TagMap::where('comic_id', '=', $comic->id)
                    ->whereHas('tag', function ($q) use ($tag_name) {
                        $q->where('name', 'like', $tag_name);
                    })
                    ->first()
                ) {
                    continue;
                }
                $tag = Tag::firstOrCreate(['name' => $tag_name]);
                $tag_map = TagMap::create(['comic_id' => $comic->id, 'tag_id' => $tag->id]);
            }
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
