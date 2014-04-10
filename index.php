
<?php 



02 
$sUrl = 'http://news.google.com/news/section?pz=1&cf=all&topic=t&ict=ln'; 



03 
$sUrlSrc = getWebsiteContent($sUrl); 



04 
  



05 
// Load the source 



06 
$dom = new DOMDocument(); 



07 
@$dom->loadHTML($sUrlSrc); 



08 
  



09 
$xpath = new DomXPath($dom); 



10 
  



11 
// step 1 - links: 



12 
$aLinks = array(); 



13 
$vRes = $xpath->query(".//*[@id='top-stories']/div/h2/a[1]"); 



14 
foreach ($vRes as $obj) { 



15 
    $aLinks[] = $obj->getAttribute('href'); 



16 
} 



17 
  



18 
// step 2 - titles: 



19 
$aTitles = array(); 



20 
$vRes = $xpath->query(".//*[@id='top-stories']/div/h2/a[1]/span"); 



21 
foreach ($vRes as $obj) { 



22 
    $aTitles[] = $obj->nodeValue; 



23 
} 



24 
  



25 
// step 3 - descriptions: 



26 
$aDescriptions = array(); 



27 
$vRes = $xpath->query(".//*[@id='top-stories']/div/div[@class='body']/div[1]"); 



28 
foreach ($vRes as $obj) { 



29 
    $aDescriptions[] = $obj->nodeValue; 



30 
} 



31 
  



32 
echo '<link href="css/styles.css" type="text/css" rel="stylesheet"/><div class="main">'; 



33 
echo '<h1>Using xpath for dom html</h1>'; 



34 
  



35 
$entries = $xpath->evaluate('.//*[@id="headline-wrapper"]/div[1]/div/h2/span'); 



36 
echo '<h1>' . $entries->item(0)->nodeValue . ' google news</h1>'; 



37 
  



38 
$i = 0; 



39 
foreach ($aLinks as $sLink) { 



40 
    echo <<<EOF 



41 
<div class="unit"> 



42 
    <a href="{$sLink}">{$aTitles[$i]}</a> 



43 
    <div>{$aDescriptions[$i]}</div> 



44 
</div> 



45 
EOF; 



46 
    $i++; 



47 
} 



48 
echo '</div>'; 



49 
  



50 
// this function will return page content using caches (we will load original sources not more than once per hour) 



51 
function getWebsiteContent($sUrl) { 



52 
    // our folder with cache files 



53 
    $sCacheFolder = 'cache/'; 



54 
  



55 
    // cache filename 



56 
    $sFilename = date('YmdH').'.html'; 



57 
  



58 
    if (! file_exists($sCacheFolder.$sFilename)) { 



59 
        $ch = curl_init($sUrl); 



60 
        $fp = fopen($sCacheFolder.$sFilename, 'w'); 



61 
        curl_setopt($ch, CURLOPT_FILE, $fp); 



62 
        curl_setopt($ch, CURLOPT_HEADER, 0); 



63 
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15')); 



64 
        curl_exec($ch); 



65 
        curl_close($ch); 



66 
        fclose($fp); 



67 
    } 



68 
    return file_get_contents($sCacheFolder.$sFilename); 



69 
} 



70 
  



71 
?> 
