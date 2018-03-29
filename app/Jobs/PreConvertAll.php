<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PreConvertAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $comics = \Comic
            ::where('converted', '=', 0)
            ->where(function ($query) {
                $query
                    ->where('one_image_size', '>', 2 * 1000 * 1000)// 2MB
                    ->orWhere('path', 'like', '%.rar');
            })
            ->get();
        foreach ($comics as $comic) {
            dispatch(new PreConvert($comic->id));
        }
    }
}
