<?php
/*
Plugin Name: WordPress Football Leagues
Plugin URI: http://ajthomas.co.uk/footballleagues
Description: A plugin to display the a chosen football league.
Version: 0.3
Author: Alex Thomas
Author URI: http://ajthomas.co.uk
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

include (WP_PLUGIN_DIR . '/wp-football-leagues/getteams.php');

function init_leagues() {
   global $wpdb;
   global $jal_db_version;

   $table_name = $wpdb->prefix . "wp_football_leagues";
      
   $sql = "CREATE TABLE $table_name (
   id tinyint(1) NOT NULL AUTO_INCREMENT,
   leaguename text NOT NULL,
   teams text NOT NULL,
   UNIQUE KEY id (id)
    );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
   $count=1;
   while($count < 5) {
		switch ($count) {
			case 1: //'Premier League':
			   $html = implode('', file('http://m.bbc.co.uk/sport/football/premier-league/table'));
			   $league = 'Premier League';
				break;
			case 2: //'Championship':
				$html = implode('', file('http://m.bbc.co.uk/sport/football/championship/table'));
				$league = 'Championship';
				break;
			case 3: //'League 1':
				$html = implode('', file('http://m.bbc.co.uk/sport/football/league-one/table'));
				$league = 'League 1';
				break;
			case 4: //'League 2':
				$html = implode('', file('http://m.bbc.co.uk/sport/football/league-two/table'));
				$league = 'League 2';
				break;
			}
		 $html = str_replace("<br/>", "", $html);       
		 $html = explode('<tbody>', $html);
		 $html = explode('</tbody>', $html[1]);
		 $html = explode('</tr>', $html[0]);
         $i = 0;
         $teams = 0;

         if (!is_numeric($limit)) {$teams = sizeof($html);}
         else {
            if ($limit == '') {$teams = sizeof($html);}
            elseif ($limit >= sizeof($html)) {$teams = $limit + 1;}
            else {$teams = $limit + 1;}
         }
         while ( $i < ($teams-1)){
		 	$row = explode('<tr', $html[$i]);
			  
			$team = explode('<td class="team">', $row[1]);
			$team = explode('</td>', $team[1]);
			$team = $team[0];
			
			$output .= $team.",";

			$i++;
		 }
		
		$db_league = $league;
		$db_output = $output;

		$rows_affected = $wpdb->insert( $table_name, array('leaguename' => $db_league, 'teams' => $db_output ) );
   
		$count++;
   }
   

}

register_activation_hook(WP_PLUGIN_DIR . '/wp-football-leagues/footleagues.php', 'init_leagues' );

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'football_league_load_widgets' );

/**
 * Register our widget.
 * 'FL_Widget' is the widget class used below.
 *
 * @since 0.1
 */

function football_league_load_widgets() {
	register_widget( 'FL_Widget' );
}

/**
 * FL Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class FL_Widget extends WP_Widget {

	function FL_Widget() {
				
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'football_league', 'description' => __('A plugin to desplay the a chosen football league.', 'football_league') );

		/* Widget control settings. */
		$control_ops = array('id_base' => 'football_league-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'football_league-widget', __('Wordpress Football Leagues', 'football_league'), $widget_ops, $control_ops );
        
        /* Setup the explode function */
        function explode_leagues($league, $limit){
          switch ($league) {
          case 'Premier League':
              $html = implode('', file('http://m.bbc.co.uk/sport/football/premier-league/table'));
              break;
          case 'Championship':
              $html = implode('', file('http://m.bbc.co.uk/sport/football/championship/table'));
              break;
          case 'League 1':
              $html = implode('', file('http://m.bbc.co.uk/sport/football/league-one/table'));
              break;
          case 'League 2':
              $html = implode('', file('http://m.bbc.co.uk/sport/football/league-two/table'));
              break;
          }
		  
		  $html = str_replace("<br/>", "", $html);       
		  $html = explode('<tbody>', $html);
		  $html = explode('</tbody>', $html[1]);
		  $html = explode('</tr>', $html[0]);
          $output = "<table><tr><th>Pos</th><th>Name</th><th>Pld</th><th>GD</th><th>Pts</th></tr>";
          $i = 0;
          $teams = 0;

          if (!is_numeric($limit)) {$teams = sizeof($html);}
          else {
              if ($limit == '') {$teams = sizeof($html);}
              elseif ($limit >= sizeof($html)) {$teams = $limit + 1;}
              else {$teams = $limit + 1;}
          }
          while ( $i < ($teams-1)){
			  $row = explode('<tr', $html[$i]);
			  
			  $team = explode('<td class="team">', $row[1]);
			  $team = explode('</td>', $team[1]);
			  $team = $team[0];
			  
			  $played = explode('<td class="played">', $row[1]);
			  $played = explode('</td>', $played[1]);
			  $played = $played[0];
			  
			  $gd = explode('<td class="goal-difference">', $row[1]);
			  $gd = explode('</td>', $gd[1]);
			  $gd = $gd[0];
			  
			  $points = explode('<td class="points">', $row[1]);
			  $points = explode('</td>', $points[1]);
			  $points = $points[0];
			  
              //$row = explode('</tr>', $row[$i]);
              $output .= "<tr><td>".($i+1)."</td><td>".$team."</td><td>".$played."</td><td>".$gd."</td><td>".$points."</td></tr>";
              $i++;
          }
          $output .= "</table>";
          echo $output;
      }
    
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$name = $instance['name'];
		$league = $instance['league'];
		$showteam = $instance['showteam'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* If show league was selected, display the user's league. */
		if ( $league )
			explode_leagues($instance['league'], $instance['limit_teams']);

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit_teams'] = strip_tags( $new_instance['limit_teams'] );
    	$instance['league'] = $new_instance['league'];
		$instance['showteam'] = $new_instance['showteam'];
		

		return $instance;
	}
	
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'showteam' => '', 'title' => __('FL', 'football_league'), 'name' => __('John Doe', 'football_league'), 'league' => 'male', 'show_league' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<!--<span id="<?php if ($instance['league'] == 'Premier League'){echo 'brian"';}?>">david</span>-->
        <p>
          <label for="<?php echo $this->get_field_name( 'league' ); ?>">League</label><br />
          <select id="<?php echo $this->get_field_id( 'league' ); ?>" name="<?php echo $this->get_field_name( 'league' ); ?>" style="width:100%;">
              <option value="Premier League" <?php if ($instance['league'] == 'Premier League'){echo 'selected="selected"';}?> >Premier League</option>
              <option value="Championship" <?php if ($instance['league'] == 'Championship'){echo 'selected="selected"';}?> >Championship</option>
              <option value="League 1" <?php if ($instance['league'] == 'League 1'){echo 'selected="selected"';}?> >League 1</option>
              <option value="League 2" <?php if ($instance['league'] == 'League 2'){echo 'selected="selected"';}?> >League 2</option>
          </select>
        </p>
        
		<p>
			<label for="<?php echo $this->get_field_id( 'limit_teams' ); ?>"><?php _e('Number of shown teams:', 'football_league'); ?></label>
			<input id="<?php echo $this->get_field_id( 'limit_teams' ); ?>" name="<?php echo $this->get_field_name( 'limit_teams' ); ?>" value="<?php echo $instance['limit_teams']; ?>" style="width:100%;" />
		</p>
		
		<!--<p>
			<label for="<?php echo $this->get_field_id( 'showteam' ); ?>"></label>
			<input type="checkbox" checked="<?php echo $instance['showteam']; ?>"> Show your team?</input>
		</p>-->
		
		<?php
			global $wpdb;
			$table_name = $wpdb->prefix . "wp_football_leagues";
			$arrLegaues = array(
				1 => $wpdb->get_var("SELECT teams FROM $table_name WHERE leaguename = 'Premier League' "),
				2 => $wpdb->get_var("SELECT teams FROM $table_name WHERE leaguename = 'Championship' "),
				3 => $wpdb->get_var("SELECT teams FROM $table_name WHERE leaguename = 'League 1' "),
				4 => $wpdb->get_var("SELECT teams FROM $table_name WHERE leaguename = 'League 2' "),
			);
			$arrLegaueNames = array(
				1 => 'premierleague',
				2 => 'championship',
				3 => 'league1',
				4 => 'league2',
			);
			
			$d = 1;
			while ($d < 5){
				
				$teams = explode(',', $arrLegaues[$d]);
				$x = 0;
				$leagueOut = '<select id="'.$arrLegaueNames[$d].'" style="display:none">';
				while ($x <= sizeof($teams)){
					$leagueOut .= '<option>'.$teams[$x].'</option>';
					$x++;
				}
						
				echo $leagueOut."</select>";
				$d++;
			}
		?>
	
		<script type="text/javascript">
		</script>
	<?php
	}
}

?>