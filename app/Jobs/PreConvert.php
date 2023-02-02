<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Subcomic\Converter;

class PreConvert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    protected $comicId;

    public function __construct(int $comicId)
    {
        $this->comicId = $comicId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $comic = \Comic::find($this->comicId);
        $numPages = (new Converter())->convert($this->comicId, $comic->getAbsolutePath());
        $comic->converted = true;
        $comic->page = $numPages;
        $comic->save();
    }
}
