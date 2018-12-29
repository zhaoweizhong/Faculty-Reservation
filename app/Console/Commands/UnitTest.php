<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\AppointmentsController;

class UnitTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faculty:unit-test {start_time} {end_time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unit Test';

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
        $controller = new AppointmentsController;
        if($controller->isFacultyAvailable(30000001, $this->argument('start_time'), $this->argument('end_time'))) {
            echo "true";
        } else {
            echo "false";
        }
    }
}
