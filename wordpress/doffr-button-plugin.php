<?php
/*
Plugin Name: Doffr.com Bitcoin Tipping
Plugin URI: http://doffr.com
Description: Bitcoin tipping for blogs, done right - Add a tip button to your posts.
Tags: doffr, bitcoin tipping, tip
Version: 1.0
Author: BitX
Author URI: https://mybitx.com
License: GPLv2
*/

if( !function_exists('wp_doffr_settings') )
{
  function wp_doffr_settings ()
  {
    //add_menu_page(page_title, menu_title, capability, handle, [function], [icon_url]);
    add_menu_page("Doffr Tipping", "Doffr Tipping", 8, basename(__FILE__), "wp_doffr_opt");
  }
}

if ( !function_exists('wp_doffr_opt') )
{
  function wp_doffr_opt()
  {
    $address = get_option("wp_doffr_address");
  ?>
  <div class="wrap">
  <div id="icon-themes" class="icon32"></div>
  <h2><strong>Doffr Tip button Settings</strong></h2>

  <?php
  if(isset($_POST['wp_doffr_form_submit']))
  {
    echo '<div style="color:green;font-weight:bold;background:#FFC;padding:4px;margin:2px 0;">Your Doffr Settings was saved successfully!</div>';
  }

  ?>

  <fieldset>
  <form name="wp_doffr_option_form" method="post">

  <h3>Select button placement on posts</h3>
  <select name="wp_doffr_align" id="wp_doffr_align">
    <option value="nb" <?php if ((get_option("wp_doffr_align") == "nb") || (!get_option("wp_doffr_align"))) echo ' selected'; ?>>None (Bottom)</option>
    <option value="nt" <?php if (get_option("wp_doffr_align") == "nt") echo 'selected'; ?>>None (Top)</option>
    <option value="tl" <?php if (get_option("wp_doffr_align") == "tl") echo 'selected'; ?>>Top Left</option>
    <option value="tr" <?php if (get_option("wp_doffr_align") == "tr") echo 'selected'; ?>>Top Right</option>
    <option value="bl" <?php if (get_option("wp_doffr_align") == "bl") echo 'selected'; ?>>Bottom Left</option>
    <option value="br" <?php if (get_option("wp_doffr_align") == "br") echo 'selected'; ?>>Bottom Right</option>
  </select>

  <h3>Bitcoin Address</h3>
  <div class="description">The Bitcoin Address where tips will be sent</div>
  <input type="text" name="wp_doffr_address" id="wp_doffr_address" value="<?= $address ?>">

  <br />
  <br />
  <div class="description">Save and enjoy your Doffr tips!</div>
  <br />
  <input type="submit" value="Save Doffr Tips" class="button-primary">
  <input type="hidden" name="wp_doffr_form_submit" value="true" />
  </form>
  <br />
  <br />

  </fieldset>

  </div>

  </form>
  <br />
  <br />

  <?php
  }
}

if( !function_exists('wp_doffr_update') )
{
  function wp_doffr_update()
  {
    if(isset($_POST['wp_doffr_form_submit']))
    {
      update_option("wp_doffr_align", $_POST['wp_doffr_align']);
      update_option("wp_doffr_address", $_POST['wp_doffr_address']);
    }
  }
}

if( !function_exists('wp_doffr_format') )
{
  function wp_doffr_format( $align )
  {
    if($align == 'left') { $margin = '5px 5px 5px 0'; }
    if($align == 'none') { $margin = '5px 0'; }
    if($align == 'right') { $margin = '5px 0 5px 5px'; }

    $address = get_option("wp_doffr_address");

    $output = '<a class="doffr-tip-button" href="#" data-address="'.$address.'">;Tip</a><script src="http://doffr.com/button.js"></script>';

    return $output;
  }
}

if ( !function_exists('wp_doffr') )
{
  function wp_doffr( $content )
  {
    if( !is_feed() && !is_page() && !is_archive() && !is_search() && !is_404() )
    {
      switch( get_option("wp_doffr_align") )
      {
        case 'tl': // Top Left
          return wp_doffr_format('left') . $content;
        break;

        case 'tr':
          return wp_doffr_format('right') . $content;
        break;

        case 'bl':
          return $content . wp_doffr_format('left');
        break;

        case 'br':
          return $content . wp_doffr_format('right');
        break;

        case 'nt': // None (Top)
          return wp_doffr_format('none') . $content;
        break;

        case 'nb': // None (Bottom)
          return $content . wp_doffr_format('none');
        break;

        default:
          return $content . wp_doffr_format('none');
      }
    }
    else
    {
      return $content;
    }
  }
}

add_filter('the_content', 'wp_doffr');
add_action('admin_menu', 'wp_doffr_settings');
add_action('init', 'wp_doffr_update');
?>