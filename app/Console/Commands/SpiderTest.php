<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Middleware\Spider;

class SpiderTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:spider {keyWords}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'php spider';

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
     *
     */
    public function handle()
    {
        $keyWords = $this->argument('keyWords');
        $asdf = new Spider();
        $linkData = $asdf->linkReader();
        $sid = 30000036;
        for ($i = 36; $i < 70; $i++) {
            $sid++;
            $src = $linkData[$i];
            $beforeTrans = $asdf->getInfo($src[2]);
            $department = (string)$src[0];
            echo $i, ':info spidered';
            $send = [
                'name' => (string)$src[1],
                'sid' => $sid,
                'password' => (string)'123456',
                'email' => (string)$sid.'@sustc.edu.cn',
                'type' => (string)'faculty',
                'department' => $department,
                'intro' => (string)'这是'.$src[1].'教授的简介。生解不预自现福亲环油出民大实的每热养路诗平际女议检饭亮们健任相，以花就计目我灯的比台，理西虽以是。行半金广有子：止不有记去，道现行北上过体青吃去件境那何时第受易史境。有心得钱觉他持产行出人工方了性城且带一妈，大兴主招可力价儿。',
                'office' => (string)'南科大某办公室',
                'fields' => (string)str_replace('，', '、', $asdf->translate($beforeTrans))
            ];
            echo ', info collected';
            $asdf->postFaculty($send);
            echo ', info stored', "\n";
        }

        //        $data = $asdf->getInfo($link);
//        $asdf->translate([]);
        //        $asdf->postFaculty();
    }
}
