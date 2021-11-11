<?php
/*
 * Created on Wed Nov 03 2021 by The MIT License (MIT) Copyright (c) 2021 Cadot.info,licence: http://spdx.org/licenses/MIT.html webmestre@cadot.info
 *
 *
 *-------------------------------------------------------------------------- *
 *      Tools.php *
 * -------------------------------------------------------------------------- *
 *
 * Usage:
 * - composer require cadot.info/symfony-testing-tools
 * - use CadotInfo\Tools;
 * - in the class: use Tools;
 *
 * Source on: https://github.com/cadot-info/tools
 */

namespace CadotInfo;

use DOMDocument;
use Zenstruck\Browser\Test\HasBrowser;


trait Tools
{
    use HasBrowser;

    //, $client = false, $urlTwoPoints = null, $urlPoint = null, array $nolinks = [], array $classRefuse = [], array $links = []

    public function returnAllLinks(string $start, int $descent = 0, array $opts, $client = null, array $links = []): array
    {
        //init default value
        $init['urlTwoPoints'] = ['mailto', 'http', 'https', 'javascript'];
        $init['urlPoint'] = ['www'];
        $init['classRefuse'] = [];
        $init['nolinks'] = [];
        $exlinks = $links;
        if (!$client) $client = $this->browser()->get($start);
        //see links of the page
        $liens = $client->response->assertHtml()->crawler();
        $htmlDom = new DOMDocument;
        //Parse the HTML of the page using DOMDocument::loadHTML
        @$htmlDom->loadHTML($liens);
        //Extract the links from the HTML.
        //$links = $htmlDom->getElementsByTagName('a');
        foreach ($htmlDom->getElementsByTagName('a') as $link) { // no get link without href
            if ($link->hasAttribute('href'))
                if ($link->getAttribute('href') != '' && !in_array($link->getAttribute('href'), $opts['nolinks'])) {
                    /** @var DOMElement $link */
                    $url = $link->getAttribute('href');
                    // pass link exist and if has not the class, not in urlpoint and urlTwoPoints
                    if (!in_array(explode(':', $url)[0], $opts['urlTwoPoints']) && (!in_array(explode('.', $url)[0], $opts['urlPoint'])) &&  !isset($exlinks[$url]) && count(array_intersect($opts[']classRefuse'], explode(' ', $link->getAttribute('class')))) == 0 && substr($url, 0, 1) != '#' && substr($url, 0, strlen('/_profiler/')) != '/_profiler/') {
                        if ($descent > 0) { // si on est dans une récursivité acceptée
                            $links = $this->returnAllLinks($url, $descent - 1, $client, $opts, $links);
                        } else {
                            $links[$url] = trim(preg_replace('/\s+/', ' ', str_replace(array("\n", "\r", ""), '', $link->nodeValue)));
                        }
                    }
                }
        }
        return $links;
    }

    /**
     * E funtion for send message immediatly
     *
     * @param  string $texte
     * @return void
     */
    public function E(string $texte): void
    {
        echo "- " . ucfirst($texte) . "\n";
        ob_flush();
    }
}
