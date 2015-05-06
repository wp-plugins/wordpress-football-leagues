<?php
/*
Plugin Name: WordPress Football Leagues
Plugin URI: http://www.ajthomas.co.uk/footballleagues
Description: A plugin to display the a chosen football league. Please <a href="https://twitter.com/share?url=http://www.ajthomas.co.uk/footballleagues/&text=Hey @ajthomascouk, I use your 'Wordpress Football Leagues' plugin on my site - *SITE URL*, It's the bomb, thanks!">let me know</a> if you use this plugin. Also please <a href="https://wordpress.org/support/view/plugin-reviews/wordpress-football-leagues">rate the plugin</a>. Thank you very much.
Version: 0.5
Author: Alex Thomas
Author URI: http://www.ajthomas.co.uk
License: A "Slug" license name e.g. GPL2
*/

/*  Copyright 2012  Alex Thomas  (email : al@ajthomas.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (is_admin()):
   wp_register_script( 'wpfl-admin-js', plugin_dir_url( __FILE__ ).'admin/footleagues-admin.js', array( 'jquery' ), '', true );
   wp_enqueue_script('wpfl-admin-js');
   wp_register_style( 'wpfl-admin-css', plugin_dir_url( __FILE__ ).'admin/footleagues-admin.css');
   wp_enqueue_style('wpfl-admin-css');
endif;
   wp_register_style( 'wpfl-css', plugin_dir_url( __FILE__ ).'footleagues.css');
   wp_enqueue_style('wpfl-css');

class wpfootballleagues extends WP_Widget
{
  function wpfootballleagues()
  {
    $widget_ops = array('classname' => 'wpfootballleagues', 'description' => 'Displays a football league of your choice' );
    $this->WP_Widget('wpfootballleagues', 'WP Football League', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance,
	  array(
		 'title' => '',
		 'country' => 'eng',
		 'league' => '',
		 'highlight-team' => false,
		 'your-team' => '',
		 'details-played' => false,
		 'details-won' => false,
		 'details-drawn' => false,
		 'details-lost' => false,
		 'details-for' => false,
		 'details-agg' => false,
		 'details-diff' => false,
		 'details-points' => false,
		 'limit-teams' => '0',
	  )
   );
    
    $title   = $instance['title'];
    $country = $instance['country'];
	$league = $instance['league'];
	$highlightTeam = $instance['highlight-team'];
	$yourTeam = $instance['your-team'];
	$detailsPlayed = $instance['details-played'];
	$detailsWon = $instance['details-won'];
	$detailsDrawn = $instance['details-drawn'];
	$detailsLost = $instance['details-lost'];
	$detailsGoalsFor = $instance['details-for'];
	$detailsGoalsAg = $instance['details-agg'];
	$detailsGoalDif = $instance['details-diff'];
	$detailsPoints = $instance['details-points'];
	$limitTeams = $instance['limit-teams'];
	$credit = $instance['credit'];

?>
	<div class="footLeagues">
	  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br /><input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></p>
  
    <p><label for="<?php echo $this->get_field_id('country'); ?>">country: </label><br />
	
	<select id="<?php echo $this->get_field_id('country'); ?>" name="<?php echo $this->get_field_name('country'); ?>">
	  <option value="eng" <?php if ($country == 'eng'){echo 'selected="selected"';}?> >England</option>
	  <option value="nor" <?php if ($country == 'nor'){echo 'selected="selected"';}?> >Norway</option>
	  <option value="sco" <?php if ($country == 'sco'){echo 'selected="selected"';}?> >Scotland</option>
	</select>
	</p>
	
	<p><label for="<?php echo $this->get_field_id('league'); ?>">League: </label><br />
	
	<select id="<?php echo $this->get_field_id('league'); ?>" name="<?php echo $this->get_field_name('league'); ?>">
	  <?php if ($country == 'eng') : ?>
		<option value="pl" <?php if ($league == 'pl'){echo 'selected="selected"';}?> >Premier League</option>
		<option value="ch" <?php if ($league == 'ch'){echo 'selected="selected"';}?> >Championship</option>
		<option value="l1" <?php if ($league == 'l1'){echo 'selected="selected"';}?> >League 1</option>
		<option value="l2" <?php if ($league == 'l2'){echo 'selected="selected"';}?> >League 2</option>
	  <?php elseif ($country == 'sco') : ?>
		<option value="spl" <?php if ($league == 'spl'){echo 'selected="selected"';}?> >Premiership</option>
		<option value="sch" <?php if ($league == 'sch'){echo 'selected="selected"';}?> >Championship</option>
		<option value="sl1" <?php if ($league == 'sl1'){echo 'selected="selected"';}?> >League 1</option>
		<option value="sl2" <?php if ($league == 'sl2'){echo 'selected="selected"';}?> >League 2</option>
	  <?php elseif ($country == 'nor') : ?>
		<option value="nor1" <?php if ($league == 'nor1'){echo 'selected="selected"';}?> >Tippeligaen</option>
	  <?php endif; ?>
	</select>
	</p>
	<p>
	  <label for="<?php echo $this->get_field_id('highlight-team'); ?>">Highlight your team?</label>
	  <input <?php if($highlightTeam):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('highlight-team'); ?>"  class="<?php echo $yourTeam; ?>" id="<?php echo $this->get_field_id('highlight-team'); ?>" />
	</p>
	<p id="changeTeam" <?php if(!$highlightTeam):echo 'style="display:none"'; endif; ?>>Chosen team: <span style="font-weight:bold"><?php echo $yourTeam; ?></span>. <span>Change?</span></p>
    <p id="your-team" style="display:none">
	<label for="<?php echo $this->get_field_id('your-team'); ?>">Choose your team?</label>
		<select id="<?php echo $this->get_field_id('your-team'); ?>" name="<?php echo $this->get_field_name('your-team'); ?>">
		</select>
    </p>
	<p>
	  <label for="<?php echo $this->get_field_id('limit-teams'); ?>">Limit teams:</label>
	  <br />Set to 0 for all teams<br />
	  <input id="<?php echo $this->get_field_id('limit-teams'); ?>" name="<?php echo $this->get_field_name('limit-teams'); ?>" type="text" value="<?php echo attribute_escape($limitTeams); ?>" />
	</p>
	<h2>Details:</h2>
	<p>
	  <label for="<?php echo $this->get_field_id('details-played'); ?>">Show games played:</label>&nbsp;
	  <input <?php if($detailsPlayed):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-played'); ?>" id="<?php echo $this->get_field_id('details-played'); ?>" />
	  <br />
	  <label for="<?php echo $this->get_field_id('details-won'); ?>">Show games won:</label>&nbsp;
	  <input <?php if($detailsWon):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-won'); ?>" id="<?php echo $this->get_field_id('details-won'); ?>" />
	  <br />
	  <label for="<?php echo $this->get_field_id('details-drawn'); ?>">Show games drawn:</label>&nbsp;
	  <input <?php if($detailsDrawn):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-drawn'); ?>" id="<?php echo $this->get_field_id('details-drawn'); ?>" />
	  <br />
	  <label for="<?php echo $this->get_field_id('details-lost'); ?>">Show games lost:</label>&nbsp;
	  <input <?php if($detailsLost):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-lost'); ?>" id="<?php echo $this->get_field_id('details-lost'); ?>" />
	  <br />
	  <label for="<?php echo $this->get_field_id('details-for'); ?>">Show goals for:</label>&nbsp;
	  <input <?php if($detailsGoalsFor):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-for'); ?>" id="<?php echo $this->get_field_id('details-for'); ?>" />
	  <br />
	  <label for="<?php echo $this->get_field_id('details-agg'); ?>">Show goals against:</label>&nbsp;
	  <input <?php if($detailsGoalsAg):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-agg'); ?>" id="<?php echo $this->get_field_id('details-agg'); ?>" />
	  <br />
	  <label for="<?php echo $this->get_field_id('details-diff'); ?>">Show goal difference:</label>&nbsp;
	  <input <?php if($detailsGoalDif):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-diff'); ?>" id="<?php echo $this->get_field_id('details-diff'); ?>" />
	  <br />
	  <label for="<?php echo $this->get_field_id('details-points'); ?>">Show Points:</label>&nbsp;
	  <input <?php if($detailsPoints):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('details-points'); ?>" id="<?php echo $this->get_field_id('details-points'); ?>" />
	</p>
	<p>
	  <label for="<?php echo $this->get_field_id('credit'); ?>">Hide credit link?:</label>&nbsp;
	  <input <?php if($credit):echo "checked"; endif; ?> type="checkbox" name="<?php echo $this->get_field_name('credit'); ?>" id="<?php echo $this->get_field_id('credit'); ?>" />
	</p>
	
	
	
	<input type="hidden" id="plugin-url" value="<?php echo plugin_dir_url( __FILE__ ); ?>" />
	</div>
	
    
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    
    // Retrieve Fields
    $instance['title']   = strip_tags($new_instance['title']);
    $instance['country'] = strip_tags($new_instance['country']);
	$instance['league'] = strip_tags($new_instance['league']);
	$instance['highlight-team'] = $new_instance['highlight-team'];
	if (strip_tags($new_instance['your-team']) != '')
	  $instance['your-team'] = strip_tags($new_instance['your-team']);
	$instance['details-played'] = $new_instance['details-played'];
	$instance['details-won'] = $new_instance['details-won'];
	$instance['details-drawn'] = $new_instance['details-drawn'];
	$instance['details-lost'] = $new_instance['details-lost'];
	$instance['details-for'] = $new_instance['details-for'];
	$instance['details-agg'] = $new_instance['details-agg'];
	$instance['details-diff'] = $new_instance['details-diff'];
	$instance['details-points'] = $new_instance['details-points'];
	if ($new_instance['limit-teams'] == '')
	  $instance['limit-teams'] = 0;
    else
	  $instance['limit-teams'] = $new_instance['limit-teams'];
    $instance['credit'] = $new_instance['credit'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
	  extract($args, EXTR_SKIP);
	
	  echo $before_widget;
	  $title   = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	  $country = $instance['country'];
	  $league = $instance['league'];
	  if (!empty($title))
		 echo $before_title . $title . $after_title;
		 
	  if (!empty($country)):
		switch ($country):
			case 'eng':
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
			break;
			case 'sco':
			  switch ($league):
				case 'spl':
					$get_league = file_get_contents('https://api.import.io/store/data/e522c6a7-892d-4247-af16-4681c64d0f40/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-premiership%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
				case 'sch':
					$get_league = file_get_contents('https://api.import.io/store/data/4d634fe9-1253-4941-bf07-53cec9b5c434/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-championship%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
				case 'sl1':
					$get_league = file_get_contents('https://api.import.io/store/data/37fe580e-2f34-4bea-8a20-2c4e39e4db73/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-league-one%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
				case 'sl2':
					$get_league = file_get_contents('https://api.import.io/store/data/88cd841c-b84b-442c-adff-2c03fd01db5e/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-league-two%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
			  endswitch;
			break;
		  case 'nor':
			  switch ($league):
				case 'nor1':
					$get_league = file_get_contents('https://api.import.io/store/data/cc74ff4f-7a33-41da-b8e3-713c9fba4248/_query?input/webpage/url=http%3A%2F%2Fwww.fotball.no%2Ffotballdata%2FTurnering%2FTabell%2F%3FtournamentId%3D143538&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
			  endswitch;
			break;
		endswitch;
		  
		 $get_league = json_decode($get_league, true);
		 
		 if ($league == 'nor1'){
			$get_league['results'] = array_splice($get_league['results'], 0, 16);
		 }
		
		 echo '<table class="wpfl"><tr>';
		 echo '<th width="50%">&nbsp;</th>';
		 if ($instance['details-played'])
		    echo '<th>Pld</th>';
		 if ($instance['details-won'])
		    echo '<th>W</th>';
		 if ($instance['details-drawn'])
		    echo '<th>D</th>';
		 if ($instance['details-lost'])
		    echo '<th>L</th>';
		 if ($instance['details-for'])
		    echo '<th>F</th>';
		 if ($instance['details-agg'])
		    echo '<th>A</th>';
		 if ($instance['details-diff'])
		    echo '<th>Dif</th>';
		 if ($instance['details-points'])
		    echo '<th>Pts</th>';
		 echo '</tr>';
		 if ($instance['limit-teams']>0)
			$i = 0;
		 foreach($get_league['results'] as $team):
			if ($instance['limit-teams']>0 && $i == $instance['limit-teams']):
			   break;
			else:
			   if ($instance['your-team'] == $team['team'] && $instance['highlight-team'])
				  echo '<tr class="highlight"><td>';
			   else
				  echo '<tr><td width="50%">';
			   echo $team['team'].'</td>';
			   if ($instance['details-played'])
				  echo '<td>'.$team['played'].'</td>';
			   if ($instance['details-won'])
				  echo '<td>'.$team['won'].'</td>';
			   if ($instance['details-drawn'])
				  echo '<td>'.$team['drawn'].'</td>';
			   if ($instance['details-lost'])
				  echo '<td>'.$team['lost'].'</td>';
			   if ($instance['details-for'])
				  echo '<td>'.$team['goals_for'].'</td>';
			   if ($instance['details-agg'])
				  echo '<td>'.$team['goals_against'].'</td>';
			   if ($instance['details-diff'])
				  echo '<td>'.$team['goal_difference'].'</td>';
			   if ($instance['details-points'])
				  echo '<td>'.$team['points'].'</td>';
			   if ($instance['your-team'] == $team['team'])
				  echo '</span>';
			   echo '</tr>';
			   $i++;
			endif;
		 endforeach;
		 echo '</table>';
		 if (!$instance['credit'])
			echo '<p id="wpfl-credit">~ Plugin by <a href="http://www.ajthomas.co.uk">ajthomascouk</a> ~</p>';
	  endif;
	
	  echo $after_widget;
  }
 
}

function wpfl_shortcode( $atts, $content = null)	{
 
	extract( shortcode_atts( array(
				'country' => 'eng',
				'league' => 'pl',
				'played' => 'true',
				'won' => 'true',
				'drawn' => 'true',
				'lost' => 'true',
				'for' => 'true',
				'against' => 'true',
				'difference' => 'true',
				'points' => 'true',
				'myteam' => '',
				'credit' => 'true',
				'limit' => 0,
				
			), $atts 
		) 
	);
	
	if (!empty($country)):
		switch ($country):
			case 'eng':
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
			break;
			case 'sco':
			  switch ($league):
				case 'spl':
					$get_league = file_get_contents('https://api.import.io/store/data/e522c6a7-892d-4247-af16-4681c64d0f40/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-premiership%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
				case 'sch':
					$get_league = file_get_contents('https://api.import.io/store/data/4d634fe9-1253-4941-bf07-53cec9b5c434/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-championship%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
				case 'sl1':
					$get_league = file_get_contents('https://api.import.io/store/data/37fe580e-2f34-4bea-8a20-2c4e39e4db73/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-league-one%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
				case 'sl2':
					$get_league = file_get_contents('https://api.import.io/store/data/88cd841c-b84b-442c-adff-2c03fd01db5e/_query?input/webpage/url=http%3A%2F%2Fwww.bbc.co.uk%2Fsport%2Ffootball%2Fscottish-league-two%2Ftable&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
			  endswitch;
			break;
		  case 'nor':
			  switch ($league):
				case 'nor1':
					$get_league = file_get_contents('https://api.import.io/store/data/cc74ff4f-7a33-41da-b8e3-713c9fba4248/_query?input/webpage/url=http%3A%2F%2Fwww.fotball.no%2Ffotballdata%2FTurnering%2FTabell%2F%3FtournamentId%3D143538&_user=4312d6e7-6d59-4722-a5ae-5c2414b9a320&_apikey=4312d6e7-6d59-4722-a5ae-5c2414b9a320%3ATZrth6CTYrdCQ97Y%2Bqagsin1MOLoW827Lqe1DE3TwrigyzAzuzT4gDpbqiCS93kMKqRpCfBGduaR8TCJIDDEUQ%3D%3D');
				    break;
			  endswitch;
			break;
		endswitch;
	  
		  
		$get_league = json_decode($get_league, true);
		
		if ($league == 'nor1'){
			$get_league['results'] = array_splice($get_league['results'], 0, 16);
		 }
		
		 $output = '<table class="wpfl"><tr>';
		$output .= '<th width="50%">&nbsp;</th>';
		 if ($played == 'true')
		    $output .= '<th>Pld</th>';
		 if ($won == 'true')
		    $output .= '<th>W</th>';
		 if ($drawn == 'true')
		    $output .= '<th>D</th>';
		 if ($lost == 'true')
		    $output .= '<th>L</th>';
		 if ($for == 'true')
		    $output .= '<th>F</th>';
		 if ($against == 'true')
		    $output .= '<th>A</th>';
		 if ($difference == 'true')
		    $output .= '<th>Dif</th>';
		 if ($points == 'true')
		    $output .= '<th>Pts</th>';
		 $output .= '</tr>';
		 if ((int)$limit>0)
			$i = 0;
		 foreach($get_league['results'] as $team):
			if ((int)$limit>0 && $i == (int)$limit):
			   break;
			else:
			   if ($team['team'] ==  $myteam || strtolower($team['team']) == $myteam)
				  $output .= '<tr class="highlight"><td>';
			   else
				  $output .= '<tr><td width="50%">';
			   $output .= $team['team'].'</td>';
			   if ($played == 'true')
				  $output .= '<td>'.$team['played'].'</td>';
			   if ($won == 'true')
				  $output .= '<td>'.$team['won'].'</td>';
			   if ($drawn == 'true')
				  $output .= '<td>'.$team['drawn'].'</td>';
			   if ($lost == 'true')
				  $output .= '<td>'.$team['lost'].'</td>';
			   if ($for == 'true')
				  $output .= '<td>'.$team['goals_for'].'</td>';
			   if ($against == 'true')
				  $output .= '<td>'.$team['goals_against'].'</td>';
			  if ($difference == 'true')
				  $output .= '<td>'.$team['goal_difference'].'</td>';
			   if ($points == 'true')
				  $output .= '<td>'.$team['points'].'</td>';
			   if ($team['team'] ==  $myteam || strtolower($team['team']) == $myteam)
				  $output .= '</span>';
			   $output .= '</tr>';
			   $i++;
			endif;
		 endforeach;
		  $output .= '</table>';
		  if ($credit == 'true')
			$output .= '<p id="wpfl-credit">~ Plugin by <a href="http://www.ajthomas.co.uk">ajthomascouk</a> ~</p>';
	  
		endif;
		 
		return $output;
 
}
add_shortcode('wpfl', 'wpfl_shortcode');

add_action( 'widgets_init', create_function('', 'return register_widget("wpfootballleagues");') );

?>