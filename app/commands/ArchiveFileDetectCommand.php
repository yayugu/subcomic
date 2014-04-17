<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class ArchiveFileDetectCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cmd:file_detect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return \ArchiveFileDetectCommand
     */
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
        $finder = Finder::create()->files()->name('/\.(?:zip|rar|pdf)\z/')->in(Config::get('subcomic.data_dir'));

        /** @var Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $path = Normalizer::normalize($file->getRelativePathname(), Normalizer::FORM_C); // For OSX
            $record = Comic::where('path', '=', $path)->first();
            if (!$record) {
                $this->info("create path:".$path);
                $record = new Comic;
                $record->path = $path;
                $record->save();
            }
        }

        $data_dir = Config::get('subcomic.data_dir');
        foreach (Comic::all() as $comic) {
            $path = $data_dir.'/'.$comic->path;
            if (!file_exists($path)) {
                $this->info("remove file which cannnot found in path:".$path);
                $comic->delete();
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
