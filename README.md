# Tools <img align="right" width="100" height="100" src="https://avatars.githubusercontent.com/u/68180174?s=80 ">

## Functions

### returnAllLinks

Testing for Panther and Web test Case
Return a array of url links of page and sub-page:

**_example:_**
`$links=$this->returnAllLinks('/');`
or

```php
$client = static::createPantherClient();
$liens = $this->returnAllLinks('/compte', 0, $client, null, null, ['bigpicture']);
```

> - string $start url of start seek
> - int $descent number or sub links
> - array $urlTwoPoints extract before : , example mailto, htpp..., default: 'mailto', 'http', 'https'
> - array $urlPoint extract before first point , example www, default 'www'
> - array $nolinks for ignore the links in the array (example logout ;-)
> - array $classRefuse don't take link if class is in this array. It's for js for example
> - array|boolean $client send a client if you have login for example
> - array $links for recursivity

#### Ignore links

- the anchor links (#...)
- link begin by "/\_profiler/"

### function E

Return a immediatly message

> -E(message)

## Utilisation

use by traits

```php
use CadotInfo\Tools;

class ClientTest extends PantherTestCase
{
   use Tools;
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
