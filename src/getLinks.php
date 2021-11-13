<?php
/*
 * Created on Wed Nov 03 2021 by The MIT License (MIT) Copyright (c) 2021 Cadot.info,licence: http://spdx.org/licenses/MIT.html webmestre@cadot.info
 *
 *
 *-------------------------------------------------------------------------- *
 *      getLinksTrait.php *
 * -------------------------------------------------------------------------- *
 *
 * Usage:
 * - composer require cadot.info/getLinks
 * - use CadotInfo\getLinks;
 * - in the class: use getLinks;
 *
 * Source on: https://github.com/cadot-info/getLinks
 */

namespace CadotInfo;

use Exception;

use DOMDocument;

function getLinks(string $start, int $descent = 0, array $options = [], array $links = [])
{
    /* ------------------------------ default value ----------------------------- */
    $opts = [
        'urlTwoPoints' => ['mailto',  'javascript'],
        'urlPoint' => [],
        'classRefuse' => [],
        'nolinks' => [],
        'noStart' => [],
        'passRefuse' => false
    ];
    // verify optioons exis
    if (count($options) > 0)
        if (count(array_intersect_key($options, $opts)) == 0)
            return new Exception('An option is not recognized');


    foreach ($opts as $key => $value)
        if (isset($options[$key]) && $options[$key] != null) $opts[$key] = $options[$key];
    $exlinks = $links;
    $htmlDom = new DOMDocument;
    if (substr($start, 0, strlen('<!DOCTYPE html>')) == '<!DOCTYPE html>')
        $htmlDom->loadHTML($start);
    else
        @$htmlDom->loadHTMLFile($start); // pass if error
    foreach ($htmlDom->getElementsByTagName('a') as $link) { // no get link without href
        if ($link->hasAttribute('href')) {
            $url = $link->getAttribute('href');
            $refuse = false;
            if (
                $url == '' || //href?
                in_array($url, $opts['nolinks']) || //link forbidden?
                in_array(explode(':', $url)[0], $opts['urlTwoPoints']) || //before : for mailto for example
                in_array(explode('.', $url)[0], $opts['urlPoint']) ||  // before . for http://noThisDomain for example
                isset($exlinks[$url])  || //i have this link
                count(array_intersect($opts['classRefuse'], explode(' ', $link->getAttribute('class')))) > 0 // no this class
            )
                $refuse = true;
            if ($refuse == false)
                foreach ($opts['noStart'] as $start)
                    if (substr($url, 0, strlen($start)) == $start) //not start
                        $refuse = true;

            if ($descent > 0) { // for test level in recurivity
                if ($opts['passRefuse'] == true || $refuse == false) $links = getLinks($url, $descent - 1, $opts, $links);
            } else {
                if ($refuse == false) $links[$url] = trim(preg_replace('/\s+/', ' ', str_replace(array("\n", "\r", ""), '', $link->nodeValue)));
            }
        }
    }
    return $links;
}
