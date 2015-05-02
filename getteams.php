<?php
	$league = $_GET['league'];
	$yourTeam = $_GET['theteam'];
	echo 'Your team: '.	$yourTeam.', League: '.$league;

;

    switch ($league):
		case 'pl':
			$get_league = file_get_contents('https://api.import.io/store/data/f9d467f1-9123-4e74-beb1-8baa01a98880/_query?input/webpage/url=http://www.bbc.co.uk/sport/football/premier-league/table&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
			break;
		case 'ch';
			$get_league = file_get_contents('https://api.import.io/store/data/0c971ab8-3007-4f4d-afad-83767f1ea59a/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fchampionship%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
			break;
		case 'l1';
			$get_league = file_get_contents('https://api.import.io/store/data/06167ff7-8c34-4f2a-9e6b-cd6a08dd61e4/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fleague-one%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
			break;
		case 'l2';
			$get_league = file_get_contents('https://api.import.io/store/data/b4add97e-e29b-4307-9337-c3cdf6b55281/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fleague-two%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
			break;
	endswitch;
    
    $get_league = json_decode($get_league, true);
    
    function compareElems($elem1, $elem2) {
        return strcmp($elem1['team'], $elem2['team']);
    }
    
    uasort($get_league['results'], "compareElems");
    
    foreach($get_league['results'] as $team): ?>
		<option value="<?php echo $team['team']; ?>" <?php if ($team['team'] == $yourTeam){echo 'selected="selected"';} ?>><?php echo $team['team']; ?></option>
	<?php endforeach;

?>