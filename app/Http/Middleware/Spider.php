<?php

namespace App\Http\Middleware;

use App\Models\User;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\Request;
use PHPExcel_IOFactory;

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

    public function linkReader(string $name)
    {
        $file = 'resources/documents/faculty_info.xlsx';
//        set_include_path(get_include_path() . PATH_SEPARATOR . './Classes/');
        include 'Classes/PHPExcel/IOFactory.php';
        $sheetData = '';
        $linkOfName = null;
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $sheetData = $objPHPExcel->getSheet(0);
            $highestRow = $sheetData->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($sheetData->getCellByColumnAndRow(1, $row) == $name) {
                    $linkOfName = $sheetData->getCellByColumnAndRow(2, $row);
                    echo $sheetData->getCellByColumnAndRow(2, $row);
                    break;
                }
            }
        } catch (\Exception $e) {
            echo 'PHPExcel Caught exception: ', $e->getMessage() . PHP_EOL;
        }
        return $linkOfName;
    }

    public function getInfo(string $url)
    {
        $headers = [
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
        ];
        $guzzleClent = new GuzzleClient([
            'timeout' => 20,
            'headers' => $headers
        ]);
        $response = $guzzleClent->request('GET', $url)->getBody()->getContents();
        $data = [];
        $crawler = new Crawler();
        $crawler->addHtmlContent($response);
        try {
            $crawler->filterXPath('//div[contains(@class, "nova-e-text nova-e-text--size-m nova-e-text--family-sans-serif nova-e-text--spacing-none nova-e-text--color-grey-900 scientific-contribution__disciplines-science")]')->each(function (Crawler $node, $i) use (&$data) {
                echo $node->text();
            });
        } catch (\Exception $e) {
            echo 'Spider Caught exception: ', $e->getMessage() . PHP_EOL;
        }
    }
}
