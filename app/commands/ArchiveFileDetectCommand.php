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
        $this->added();
        $this->delete();
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

    protected function added()
    {
        $this->info("added start");
        $finder = Finder::create()->files()->name('/\.(?:zip|rar|pdf)\z/')->in(Config::get('subcomic.data_dir'));

        $buffer = [];
        $index = 0;

        /** @var Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $path = Normalizer::normalize($file->getRelativePathname(), Normalizer::FORM_C); // For OSX
            $record = new Comic;
            $record->path = $path;
            $sha1 = sha1($record->getFileName(), true);
            $buffer[] = [
                $path,
                $sha1,
            ];
            $index++;
            if (count($buffer) >= 4000) {
                $this->bulkInsert($buffer);
                $buffer = [];
                $this->info($index);
            }

        }
        $this->bulkInsert($buffer);
        $this->info($index);
        $this->info("added end");
    }

    protected function bulkInsert($array)
    {
        if (count($array) <= 0) {
            return;
        }
        $base = 'insert into comics (`path`, `filename_sha1`, `updated_at`, `created_at`) VALUES ';
        $base2 = 'on duplicate key update `filename_sha1` = values(`filename_sha1`)';
        $pattern = '(?, ?, ?, ?)';
        $date_string = date('Y-m-d H:i:s');

        $query = $base;
        for ($i = 0; $i < count($array) - 1; $i++) {
            $query .= $pattern . ', ';
        }
        $query .= $pattern;
        $query .= $base2;
        $bindings = array_reduce($array, function($carry, $item) use($date_string) {
            $carry[] = $item[0];
            $carry[] = $item[1];
            $carry[] = $date_string;
            $carry[] = $date_string;
            return $carry;
        }, []);

        DB::insert($query, $bindings);
    }

    protected function delete()
    {
        $this->info("delete start");
        $data_dir = Config::get('subcomic.data_dir');
        foreach (Comic::all() as $comic) {
            $path = $data_dir.'/'.$comic->path;
            if (!file_exists($path)) {
                $this->info("remove file which cannnot found in path:".$path);
                $comic->delete();
            }
        }
        $this->info("delete end");
    }

}
