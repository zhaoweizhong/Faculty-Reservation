<?php

namespace App\Console\Commands;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Middleware\Spider;

class SpiderTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:spider {keyWords*}';

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
//        $url = "https://www.researchgate.net/scientific-contributions/39548198_Bin_Tan";
//        $headers = [
//            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
//        ];
//        $guzzleClent = new GuzzleClient([
//            'timeout' => 20,
//            'headers' => $headers
//        ]);
//        $response = $guzzleClent->request('GET', $url)->getBody()->getContents();
//        $data = [];
//        $crawler = new Crawler();
//        $crawler->addHtmlContent($response);
//        try {
//            $crawler->filterXPath('//div[contains(@class, "nova-e-text nova-e-text--size-m nova-e-text--family-sans-serif nova-e-text--spacing-none nova-e-text--color-grey-900 scientific-contribution__disciplines-science")]')->each(function (Crawler $node, $i) use (&$data){
//                echo $node->text();
//            });
//        } catch (\Exception $e) {
//            echo 'Spider Caught exception: ', $e->getMessage() . PHP_EOL;
//        }
        $asdf = new Spider();
        $link = $asdf->linkReader('李鹏飞');
        $asdf->getInfo($link);
    }
}
