<?php

function explode_leagues($league, $limit){
    switch ($league) {
    case 'Premier League':
        $html = implode('', file('http://news.bbc.co.uk/mobile/bbc_sport/football/competition/100/table/index.shtml'));
        break;
    case 'Championship':
        $html = implode('', file('http://news.bbc.co.uk/mobile/bbc_sport/football/competition/101/table/index.shtml'));
        break;
    case 'League 1':
        $html = implode('', file('http://news.bbc.co.uk/mobile/bbc_sport/football/competition/102/table/index.shtml'));
        break;
    case 'League 2':
        $html = implode('', file('http://news.bbc.co.uk/mobile/bbc_sport/football/competition/103/table/index.shtml'));
        break;
    }
    $html = str_replace("<br/>", "", $html);       
    $html = explode('<table class="tblResults"', $html);
    $html = explode('</table>', $html[1]);
    $html = explode('<tr class="tblRow', $html[0]);
    $output = "<table><tr><th>Pos</th><th>Name</th><th>Pld</th><th>GD</th><th>Pts</th></tr>";
    $i = 1;
    $teams = 0;
    if (!is_numeric($limit)) {$teams = sizeof($html);}
    else {
        if ($limit == '') {$teams = sizeof($html);}
        elseif ($limit >= sizeof($html)) {$teams = $limit + 1;}
        else {$teams = $limit + 1;}
    }   
    while ( $i < $teams){
        $row = explode('">', $html[$i]);
        $row = explode('</tr>', $row[1]);
        $output .= "<tr><td>".$i."</td>".$row[0]."</tr>";
        $i++;
    }
    $output .= "</table>";
    echo $output;
}

?>