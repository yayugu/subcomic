<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class UnZipCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:unzip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $finder = new Finder;
        $finder->files()->in('/Users/yayugu/comic');
        $it = $finder->getIterator();
        $it->next();
        $file = $it->current();

        $path = $file->getRealPath();

        $zip = new ZipArchive;
        if (!$zip->open($path)) {
            $this->error("zip open failed");
            return;
        }
        //$zip->extractTo("/tmp/comic/");
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $value = $zip->getFromIndex($i);
            $name = $zip->getNameIndex($i);
            if ($value === false || preg_match("/MACOS/", $name)) {
                continue;
            }
            $fullpath = "/tmp/comic/" . $name;
            $dirname = dirname($fullpath);

            $this->info("search: " . $dirname);
            if (!is_dir($dirname)) {
                $this->info("create: " . $dirname);
                if (!mkdir($dirname, 0777, true)) {
                    $this->error("mkdir failed");
                }
            }
            try {
                $im = new Imagick;
                $im->readimageblob($value, $name);
                $data = pathinfo($name);
                $ext = $data['extension'];
                $im->setimageformat($ext);
                $im->sepiatoneimage(80);
                $im->writeimage($fullpath);
            } catch (Exception $e) {
                $this->error(strlen($value));
                $this->error($value);
                $this->error($e->getMessage());
                $this->error($name);
            }

        }
        $zip->close();
        $this->info("succeed");

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(//array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
