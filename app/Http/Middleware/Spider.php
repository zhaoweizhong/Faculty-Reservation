<?php

namespace App\Http\Middleware;

use App\Models\User;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use function GuzzleHttp\Psr7\str;
use GuzzleRetry\GuzzleRetryMiddleware;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use App\Http\Controllers\Api\SpiderController;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Google\Cloud\Translate\TranslateClient;

class Spider
{
    /**
     * Class for handling information crawler
     *
     * @param string $doi
     * @return bool|string
     */

    public function getTrend(string $doi)
    {
        $url = 'https://doi.org/' . $doi;
        $header = array('Accept: text/x-bibliography');
        $content = array(
            'style' => 'apa'
        );
        $response = $this->toCurl($url, $header, $content);
        return $response;
    }

    function toCurl($url, $header, $content)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
        $response = curl_exec($ch);
        if ($error = curl_error($ch)) {
            die($error);
        }
        curl_close($ch);
        return $response;
    }

    public function linkReader()
    {
        $file = 'resources/documents/faculty_info.xlsx';
        //        set_include_path(get_include_path() . PATH_SEPARATOR . './Classes/');
        include 'Classes/PHPExcel/IOFactory.php';
        $sheetData = '';
        $linkOfName = null;
        $target = [];
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $sheetData = $objPHPExcel->getSheet(0);
            $highestRow = $sheetData->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $individual = [];
                $department = $sheetData->getCellByColumnAndRow(0, $row);
                $name = $sheetData->getCellByColumnAndRow(1, $row);
                $linkOfName = $sheetData->getCellByColumnAndRow(2, $row);
                $individual = [$department, $name, $linkOfName];
                $target[] = $individual;
            }
        } catch (\Exception $e) {
            echo 'PHPExcel Caught exception: ', $e->getMessage() . PHP_EOL;
        }
        return $target;
    }

    public function getInfo(string $url)
    {
        $option = false;
        if (strcmp(substr($url, 29, 7), 'profile') == 0) {
            $option = true;
        }

        $stack = HandlerStack::create();
        $stack->push(GuzzleRetryMiddleware::factory());
        $headers = [
            'User-Agent' => 'testing/1.0',
            //            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
        ];
        $requestOption = [
            'timeout' => 20,
            'headers' => $headers,
            'handler' => $stack,
            'retry_on_status' => [429],
            'max_retry_attempts' => 5
        ];
        $guzzleClent = new GuzzleClient($requestOption);
        $response = $guzzleClent->request('GET', $url)->getBody()->getContents();
        $data = [];
        $crawler = new Crawler();
        $crawler->addHtmlContent($response);
        try {
            if ($option) {
                $crawler->filterXPath('//a[contains(@class, "nova-e-badge nova-e-badge--color-grey nova-e-badge--display-block nova-e-badge--luminosity-medium nova-e-badge--size-l nova-e-badge--theme-ghost nova-e-badge--radius-full")]')->each(function (Crawler $node, $i) use (&$data) {
                    $data[] = $node->text();
                });
            } else {
                $crawler->filterXPath('//div[contains(@class, "nova-e-text nova-e-text--size-m nova-e-text--family-sans-serif nova-e-text--spacing-none nova-e-text--color-grey-900 scientific-contribution__disciplines-science")]')->each(function (Crawler $node, $i) use (&$data) {
                    $data[] = $node->text();
                });
            }
        } catch (\Exception $e) {
            echo 'Spider Caught exception: ', $e->getMessage() . PHP_EOL;
        }
        return $data;
    }

    public function translate(array $sourceData)
    {
        $counts = count($sourceData);
        if ($counts == 0) {
            return (string)'';
        }
        if ($counts > 5) {
            $counts = 5;
        }
        $targetData = $sourceData[0];
        for ($row = 1; $row < $counts; $row++) {
            $targetData = $targetData . ',' . $sourceData[$row];
        }
        $projectId = 'grand-landing-165615';
        $translate = new TranslateClient([
            'projectId' => $projectId
        ]);
        $target = 'zh';
        $translation = $translate->translate($targetData, [
            'target' => $target
        ]);
        return $translation['text'];
//        $tran = new GoogleTranslate();
//        $tran->setSource('en');
//        $tran->setTarget('zh');
//        $tran->setOptions([
//            'timeout' => 10,
////            'proxy' => [
////                'https'  => 'tcp://localhost:1087'
////            ],
//            'headers' => [
//                'User-Agent' => 'Foo/5.0 Lorem Ipsum Browser'
//            ]
//        ]);
//        $tran->setUrl('https://translation.googleapis.com/language/translate/v2');
//        try {
//            echo $tran->translate('sentence');
//        } catch (\Exception $e) {
//            echo 'Google translate Caught exception: ', $e->getMessage() . PHP_EOL;
//        }

//        $target = $sourceData[0];
//        for ($row = 1; $row < count($sourceData); $row++) {
//            $target = $target . ',' . $sourceData[$row];
//        }
//        return $target;
    }

    public function postFaculty($data)
    {
        $test = new SpiderController();
        $test->create($data);
    }
}
