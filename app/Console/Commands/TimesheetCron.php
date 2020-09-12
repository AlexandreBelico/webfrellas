<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class TimesheetCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timesheet:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly timesheet details on each monday';

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
    public function handle()
    {
        Log::info('Cron Job Started');
    }
}
