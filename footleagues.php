<?php
/*
Plugin Name: WordPress Football Leagues
Plugin URI: http://ajthomas.co.uk/wp-football-leagues
Description: A plugin to desplay the a chosen football league.
Version: 0.1.2
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

error_reporting(E_ALL);
add_action("widgets_init", array('WpFootballLeagues', 'register'));
register_activation_hook( __FILE__, array('WpFootballLeagues', 'activate'));
register_deactivation_hook( __FILE__, array('WpFootballLeagues', 'deactivate'));
class WpFootballLeagues {
  function activate(){
    $data = array( 'title' => 'WordPress Football Leagues', 'show_title' => 'Yes', 'league' => 'Premier League', 'limit_teams' => '');
    if ( ! get_option('WpFootballLeagues')){
      add_option('WpFootballLeagues' , $data);
    } else {
      update_option('WpFootballLeagues' , $data);
    }
  }
  function deactivate(){
    delete_option('WpFootballLeagues');
  }
    function control(){
      $data = get_option('WpFootballLeagues');
      ?>
      <p>
        <label>Title</label><br />
        <input name="WpFootballLeagues_title" type="text" value="<?php echo $data['title']; ?>" />
      </p>
      <p>
        <label>Show title?</label><br />
        <input type="radio" name="WpFootballLeagues_show_title" value="Yes" <?php if ($data['show_title'] == 'Yes'){echo 'checked';}?> /> Yes
        <input type="radio" name="WpFootballLeagues_show_title" value="No" <?php if ($data['show_title'] == 'No'){echo 'checked';}?> /> No 
      </p>
      <p>
        <label>League</label><br />
        <select name="WpFootballLeagues_league">
            <option value="Premier League" <?php if ($data['league'] == 'Premier League'){echo 'selected="selected"';}?> >Premier League</option>
            <option value="Championship" <?php if ($data['league'] == 'Championship'){echo 'selected="selected"';}?> >Championship</option>
            <option value="League 1" <?php if ($data['league'] == 'League 1'){echo 'selected="selected"';}?> >League 1</option>
            <option value="League 2" <?php if ($data['league'] == 'League 2'){echo 'selected="selected"';}?> >League 2</option>
        </select>
      </p>
      <p>
        <label>Number of shown teams</label><br />
        <input name="WpFootballLeagues_limit_teams" type="text" value="<?php echo $data['limit_teams']; ?>" />
      <?php
       if (isset($_POST['WpFootballLeagues_league'])){
        $data['title'] = attribute_escape($_POST['WpFootballLeagues_title']);
        $data['show_title'] = attribute_escape($_POST['WpFootballLeagues_show_title']);
        $data['league'] = attribute_escape($_POST['WpFootballLeagues_league']);
        $data['limit_teams'] = attribute_escape($_POST['WpFootballLeagues_limit_teams']);
        update_option('WpFootballLeagues', $data);
      }
    }
  function widget($args){
    include 'leagues.php';
    $data = get_option('WpFootballLeagues');
    echo $args['before_widget'];
    if ($data['show_title'] == 'Yes'){echo $args['before_title'] . $data['title'] . $args['after_title'];}
    explode_leagues($data['league'], $data['limit_teams']);
    echo $args['after_widget'];
  }
  function register(){
    register_sidebar_widget('WordPress Football Leagues', array('WpFootballLeagues', 'widget'));
    register_widget_control('WordPress Football Leagues', array('WpFootballLeagues', 'control'));
  }
}

?>