<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UserAddCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:user_add';

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
        $user = User::firstOrNew(['name' => $this->argument('name')]);
        $user->setPassword($this->argument('password'));
        if ($user->save()) {
            $this->info('save succeed');
        } else {
            $this->info('save failed');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'name'],
            ['password', InputArgument::REQUIRED, 'password'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array( //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}