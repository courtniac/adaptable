<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details
 *
 * @package   theme_adaptable
 * @copyright 2015 Jeremy Hopkins (Coventry University)
 * @copyright 2015-2017 Fernando Acedo (3-bits.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();

// Set HTTPS if needed.
if (empty($CFG->loginhttps)) {
    $wwwroot = $CFG->wwwroot;
} else {
    $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
}

// Check if this is a course or module page and check setting to hide site title.  If not one of these pages, by default
// show it (set $hidesitetitle to false).
if ( (strstr($PAGE->pagetype, 'course')) ||
     (strstr($PAGE->pagetype, 'mod')) && ($this->page->course->id > 1) ) {
    $hidesitetitle = !empty(($PAGE->theme->settings->coursepageheaderhidesitetitle)) ? true : false;
} else {
    $hidesitetitle = false;
}


// Screen size.
theme_adaptable_initialise_zoom($PAGE);
$setzoom = theme_adaptable_get_zoom();

theme_adaptable_initialise_full($PAGE);
$setfull = theme_adaptable_get_full();


// Navbar.
if (isset($PAGE->theme->settings->stickynavbar) && $PAGE->theme->settings->stickynavbar == 1
    && $PAGE->pagetype != "grade-report-grader-index" && $PAGE->bodyid != "page-grade-report-grader-index") {
    $fixedheader = true;
} else {
    $fixedheader = false;
}

$PAGE->requires->js_call_amd('theme_adaptable/bsoptions', 'init', array($fixedheader));


// Layout.
$left = (!right_to_left());  // To know if to add 'pull-right' and 'desktop-first-column' classes in the layout for LTR.

$hasmiddle = $PAGE->blocks->region_has_content('middle', $OUTPUT);
$hasfootnote = (!empty($PAGE->theme->settings->footnote));

$hideheadermobile = $PAGE->theme->settings->hideheadermobile;
$hidealertsmobile = $PAGE->theme->settings->hidealertsmobile;
$hidesocialmobile = $PAGE->theme->settings->hidesocialmobile;


// Load header background image if exists.
$headerbg = '';

if (!empty($PAGE->theme->settings->headerbgimage)) {
    $headerbg = ' style="background-image: url('.$PAGE->theme->setting_file_url('headerbgimage', 'headerbgimage').');
                         background-position: 0 0; background-repeat: no-repeat; background-size: cover;"';
}


// Get the fonts name.
$fontname = str_replace(" ", "+", $PAGE->theme->settings->fontname);
$fontheadername = str_replace(" ", "+", $PAGE->theme->settings->fontheadername);
$fonttitlename = str_replace(" ", "+", $PAGE->theme->settings->fonttitlename);


// Get the fonts subset.
if (!empty($PAGE->theme->settings->fontsubset)) {
    $fontssubset = '&subset=latin,'.$PAGE->theme->settings->fontsubset;
} else {
    $fontssubset = '';
}


// Font weights.
if (!empty($PAGE->theme->settings->fontweight)) {
    $fontweight = ':'.$PAGE->theme->settings->fontweight.','.$PAGE->theme->settings->fontweight.'i';
} else {
    $fontweight = ':400,400i';
}

if (!empty($PAGE->theme->settings->fontheaderweight)) {
    $fontheaderweight = ':'.$PAGE->theme->settings->fontheaderweight.','.$PAGE->theme->settings->fontheaderweight.'i';
} else {
    $fontheaderweight = ':400,400i';
}

if (!empty($PAGE->theme->settings->fonttitleweight)) {
    $fonttitleweight = ':'.$PAGE->theme->settings->fonttitleweight.','.$PAGE->theme->settings->fonttitleweight.'i';
} else {
    $fonttitleweight = ':700,700i';
}

// Get the HTML for the settings bits.
$html = theme_adaptable_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

// Social icons class.
$showicons = "";
$showicons = $PAGE->theme->settings->blockicons;
if ($showicons == 1) {
    $showiconsclass = "showblockicons";
} else {
    $showiconsclass = " ";
}

// Setting for default screen view. Does not override user's preference.
$defaultview = "";
$defaultview = $PAGE->theme->settings->viewselect;
if ($defaultview == 1 && $setfull == "") {
    $setfull = "fullin";
}


// HTML header.
echo $OUTPUT->doctype();
?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="icon" href="<?php echo $OUTPUT->favicon(); ?>" />

    <link rel="stylesheet" href="<?php p($CFG->httpswwwroot) ?>/theme/adaptable/style/font-awesome.min.css">

<?php

// Load Google Fonts.
if (!empty($fontname) && $fontname != 'default') {
?>
    <link href='https://fonts.googleapis.com/css?family=<?php echo $fontname.$fontweight.$fontssubset; ?>'
    rel='stylesheet'
    type='text/css'>
<?php
}
?>

<?php
if (!empty($fontheadername) && $fontheadername != 'default') {
?>
    <link href='https://fonts.googleapis.com/css?family=<?php echo $fontheadername.$fontheaderweight.$fontssubset; ?>'
    rel='stylesheet'
    type='text/css'>
<?php
}
?>

<?php
if (!empty($fonttitlename)  && $fonttitlename != 'default') {
?>
    <link href='https://fonts.googleapis.com/css?family=<?php echo $fonttitlename.$fonttitleweight.$fontssubset; ?>'
    rel='stylesheet'
    type='text/css'>
<?php
}

// HTML head.
echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Twitter Card data -->
    <meta name="twitter:card" value="summary">
    <meta name="twitter:site" value="<?php echo $SITE->fullname; ?>" />
    <meta name="twitter:title" value="<?php echo $OUTPUT->page_title(); ?>" />

    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo $OUTPUT->page_title(); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $CFG->wwwroot; ?>" />
    <meta property="og:site_name" content="<?php echo $SITE->fullname; ?>" />

    <!-- Chrome, Firefox OS and Opera on Android -->
    <meta name="theme-color" content="<?php echo $PAGE->theme->settings->maincolor; ?>" />

    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="<?php echo $PAGE->theme->settings->maincolor; ?>" />

    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $PAGE->theme->settings->maincolor; ?>" />
</head>

<body <?php echo $OUTPUT->body_attributes(array('two-column', $setzoom)); ?>>

<?php echo $OUTPUT->standard_top_of_body_html();

    // Development or wrong moodle version alert.
    echo $OUTPUT->get_dev_alert();
?>

<div id="page" class="container-fluid <?php echo "$setfull $showiconsclass"; ?>">

<?php
// If the device is a mobile and the alerts are not hidden or it is a desktop then load and show the alerts.
if (((theme_adaptable_is_mobile()) && ($hidealertsmobile == 1)) || (theme_adaptable_is_desktop())) {
    // Display alerts.
    echo $OUTPUT->get_alert_messages();
}

// [DELETED] Background image in Header.
?>
    <header id="page-header-wrapper" <?php echo $headerbg; ?> >
<?php
// [DELETED] Login button.
// [DELETED] Show user avatar.
// [DELETED] Show username based in fullnamedisplay variable.
// [DELETED] Add top menus.
// [DELETED] Add messages / notifications (moodle 3.2 or higher).
?>

<?php
// [MODIFY?] If it is a mobile and the header is not hidden or it is a desktop then load and show the header.
// [DELETED] Site title or logo.
// [DELETED] Social icons.
// [DELETED] Search box.
?>

<?php
// Navbar Menu.
if ( (isloggedin() && !isguestuser())
   || (!empty($PAGE->theme->settings->enablenavbarwhenloggedout)) ) {
?>
    <div id="navwrap">
        <div class="container">
            <div class="navbar">
                <nav role="navigation" class="navbar-inner">
                    <div class="container-fluid">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <div class="nav-collapse collapse ">
                            <?php echo $OUTPUT->navigation_menu(); ?>
<?php
// [ADDED] Site logo.
if (!$hidesitetitle) {
    echo $OUTPUT->get_logo_title();
}
?>
<?php
if (empty($PAGE->theme->settings->disablecustommenu)) {
        echo $OUTPUT->custom_menu();
}
?>
<?php
if ($PAGE->theme->settings->enabletoolsmenus) {
        echo $OUTPUT->tools_menu();
}
?>

        <ul class="nav pull-right">
<?php
if (isloggedin()) {
    if ($PAGE->theme->settings->enableshowhideblocks) { ?>
           <li class="hbl">
               <a href="#" class="moodlezoom" title="<?php echo get_string('hideblocks', 'theme_adaptable') ?>">
                   <i class="fa fa-indent fa-lg"></i>
               <span class="zoomdesc"><?php echo get_string('hideblocks', 'theme_adaptable') ?></span>
           </a>
       </li>
       <li class="sbl">
               <a href="#" class="moodlezoom" title="<?php echo get_string('showblocks', 'theme_adaptable') ?>">
               <i class="fa fa-outdent fa-lg"></i>
               <span class="zoomdesc"><?php echo get_string('showblocks', 'theme_adaptable') ?></span>
           </a>
       </li>
<?php
    }

    if ($PAGE->theme->settings->enablezoom) { ?>
            <li class="hbll">
                <a href="#" class="moodlewidth" title="<?php echo get_string('fullscreen', 'theme_adaptable') ?>">
                <i class="fa fa-expand fa-lg"></i>
                <span class="zoomdesc"><?php echo get_string('fullscreen', 'theme_adaptable') ?></span>
            </a>
        </li>
        <li class="sbll">
                <a href="#" class="moodlewidth" title="<?php echo get_string('standardview', 'theme_adaptable') ?>">
                    <i class="fa fa-compress fa-lg"></i>
                <span class="zoomdesc"><?php echo get_string('standardview', 'theme_adaptable') ?></span>
            </a>
            </li>
<?php
    }
}
?>
<?php
if (!isloggedin() || isguestuser()) {
    echo $OUTPUT->page_heading_menu();

    if ($PAGE->theme->settings->displaylogin == 'box') {
        // [ADDED] Login button.
?>
        <form action="<?php p($wwwroot) ?>/login/index.php" method="post">
            <input type="text" name="username"
                    placeholder="<?php echo get_string('loginplaceholder', 'theme_adaptable'); ?>" size="10">
            <input type="password" name="password"
                    placeholder="<?php echo get_string('passwordplaceholder', 'theme_adaptable'); ?>"  size="10">
            <button class="btn-login" type="submit"><?php echo get_string('logintextbutton', 'theme_adaptable'); ?></button>
        </form>
<?php
    } else if ($PAGE->theme->settings->displaylogin == 'button') {
?>
        <form action="<?php p($wwwroot) ?>/login/index.php" method="post">
            <button class="btn-login" type="submit">
                <?php echo get_string('logintextbutton', 'theme_adaptable'); ?>
            </button>
        </form>
<?php
    }
} else {
?>
<!-- [ADDED] ADD MATERIAL BUTTON
<button type="button" class="btn btn-default">
  <span class="fa fa-trash-o fa-lg" aria-hidden="true"></span>
</button> -->
        <div class="dropdown secondone">
            <a class="dropdown-toggle usermendrop" data-toggle="dropdown">

<?php
    // [ADDED] Show user avatar.
    $userpic = $OUTPUT->user_picture($USER, array('link' => false, 'size' => 80, 'class' => 'userpicture'));
    echo $userpic;

?>
                <span class="fa fa-angle-down"></span>
            </a>

    <ul class="dropdown-menu usermen" role="menu">

<?php echo $OUTPUT->user_profile_menu() ?>

    </ul>
</div>
  <div class="singlebutton">
    <form method="get" action="http://localhost:8888/moodle35/course/edit.php">
      <div>
        <input type="submit" value="Add class">
        <input type="hidden" name="category" value="1">
        <input type="hidden" name="returnto" value="topcat">
      </div>
    </form>
  </div>
  <div class="singlebutton">
    <form method="get" action="http://localhost:8888/moodle35/course/modedit.php">
      <div>
        <input type="submit" value="Add material">
        <input type="hidden" name="add" value="url">
        <input type="hidden" name="type" value="">
        <input type="hidden" name="return" value="0">
        <input type="hidden" name="sr" value="">
        <input type="hidden" name="course" value="1">
        <input type="hidden" name="section" value="1">
      </div>
    </form>
  </div>
<?php } ?>
        </ul>
                            <div id="edittingbutton" class="pull-right breadcrumb-button">
                                <?php echo $OUTPUT->page_heading_button(); ?>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
<?php
}
?>

</header>

<?php

// [DELETED] Display News Ticker.
