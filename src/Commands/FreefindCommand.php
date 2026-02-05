<?php

namespace Freefind\Freefind\Commands;

use Illuminate\Console\Command;

class FreefindCommand extends Command
{
    public $signature = 'freefind-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
