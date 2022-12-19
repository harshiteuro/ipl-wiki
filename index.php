<?php // For broken web servers: ><pre>

// If you are reading this in your web browser, your server is probably
// not configured correctly to run PHP applications!
//
// See the README, INSTALL, and UPGRADE files for basic setup instructions
// and pointers to the online documentation.
//
// https://www.mediawiki.org/wiki/Special:MyLanguage/MediaWiki
//
// -------------------------------------------------

/**
 * The main web entry point for web browser navigations, usually via an
 * Action or SpecialPage subclass.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup entrypoint
 */

define( 'MW_ENTRY_POINT', 'index' );
// use MediaWiki\MediaWikiServices;

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies. Using dirname( __FILE__ ) here because __DIR__ is PHP5.3+.
// phpcs:ignore MediaWiki.Usage.DirUsage.FunctionFound
require_once dirname( __FILE__ ) . '/includes/PHPVersionCheck.php';

$JSON_DATA=file_get_contents('./dataset/teams.json');

$JSON_ARR=json_decode($JSON_DATA);


wfEntryPointCheck( 'html', dirname( $_SERVER['SCRIPT_NAME'] ) );

require __DIR__ . '/includes/WebStart.php';


//SCRIPT
$endPoint = "http://localhost/ipl-wiki/api.php";
$login_Token = getLoginToken(); // Step 1
loginRequest( $login_Token ); // Step 2
$csrf_Token = getCSRFToken(); // Step 3

//creating Ipl table
$table="{| class='wikitable' \n|+ List of IPL Teams \n|- \n! S_NO !! PAGE TITLE \n|-\n";

for($i=0;$i<count($JSON_ARR);$i++){
	$appendText="The team on id ".$JSON_ARR[$i]->Team_Id." is ".$JSON_ARR[$i]->Team_Name." .The team is also known as ".$JSON_ARR[$i]->Team_Short_Code.".";
	editRequest($csrf_Token,"IPL-".$JSON_ARR[$i]->Team_Name,$appendText);
	if($i==count($JSON_ARR)-1){
		$table=$table."| ".($i+1)." || ".$JSON_ARR[$i]->Team_Name." \n|}";
	}
	else{
		$table=$table."| ".($i+1)." || ".$JSON_ARR[$i]->Team_Name." \n|-\n";
	}
}
editRequest($csrf_Token,"IPL Teams 2022",$table);


wfIndexMain();


//APIs
// Step 1: GET request to fetch login token
function getLoginToken() {
	global $endPoint;

	$params1 = [
		"action" => "query",
		"meta" => "tokens",
		"type" => "login",
		"format" => "json"
	];

	$url = $endPoint . "?" . http_build_query( $params1 );

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_COOKIEJAR, "cookie.txt" );
	curl_setopt( $ch, CURLOPT_COOKIEFILE, "cookie.txt" );

	$output = curl_exec( $ch );
	curl_close( $ch );

	$result = json_decode( $output, true );
	return $result["query"]["tokens"]["logintoken"];
}

// Step 2: POST request to log in. Use of main account for login is not
// supported. Obtain credentials via Special:BotPasswords
// (https://www.mediawiki.org/wiki/Special:BotPasswords) for lgname & lgpassword
function loginRequest( $logintoken ) {
	global $endPoint;

	$params2 = [
		"action" => "login",
		"lgname" => "Harshitdev",
		"lgpassword" => "harshit1@@",
		"lgtoken" => $logintoken,
		"format" => "json"
	];

	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_URL, $endPoint );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params2 ) );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_COOKIEJAR, "cookie.txt" );
	curl_setopt( $ch, CURLOPT_COOKIEFILE, "cookie.txt" );

	$output = curl_exec( $ch );
	curl_close( $ch );

}

// Step 3: GET request to fetch CSRF token
function getCSRFToken() {
	global $endPoint;

	$params3 = [
		"action" => "query",
		"meta" => "tokens",
		"format" => "json"
	];

	$url = $endPoint . "?" . http_build_query( $params3 );

	$ch = curl_init( $url );

	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_COOKIEJAR, "cookie.txt" );
	curl_setopt( $ch, CURLOPT_COOKIEFILE, "cookie.txt" );

	$output = curl_exec( $ch );
	curl_close( $ch );

	$result = json_decode( $output, true );
	return $result["query"]["tokens"]["csrftoken"];
}

// Step 4: POST request to edit a page
function editRequest( $csrftoken,$title,$appendText ) {
	global $endPoint;

	$params4 = [
		"action" => "edit",
		"title" => $title,
		"appendtext" => $appendText,
		"token" => $csrftoken,
		"format" => "json"
	];

	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_URL, $endPoint );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params4 ) );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_COOKIEJAR, "cookie.txt" );
	curl_setopt( $ch, CURLOPT_COOKIEFILE, "cookie.txt" );

	$output = curl_exec( $ch );
	curl_close( $ch );

	return $output;
}


function wfIndexMain() {
	// @todo Deprecate global $mediaWiki
	// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
	global $mediaWiki;
	$mediaWiki = new MediaWiki();
	$mediaWiki->run();
}
