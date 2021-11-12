# Get All Links of web page or html string

Return a array of url links of page and sub-page:

**_example:_**
`$links=$this->returnAllLinks('https://github.com');`

or by html

`$links = $this->returnAllLinks($html);`

### Many options

-urlTwoPoints => refuse the links before : ,for example with mailto,javascript,..
-urlPoint => refuse the links before . ,for example https://github.
-classRefuse => refuse the links with this classes, example: bigpicture button ...
-nolinks => refuse this links for example https:github.com, www.google.com ...
-noStart => refuse link start for example /profiler, http://google
-passRefuse => if true, if a link is refused, the code seek in this link for recursivity

## Utilisation

use by traits

```php
use CadotInfo\getLinks;

class ...
{
   use getLinks;
   ...
   $liens = $this->returnAllLinks('/', 1, null, ['mailto',  'javascript'], [''], ['bigpicture']);
        foreach ($liens as $url => $texte) {
            dump("Test url:$url(texte)");
   ...
   $this->E('test of links');

```

### tests links

(http://google.fr)
(http://thispagedontexiste.exist)
