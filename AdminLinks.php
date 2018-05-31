<?php
/**
 * A special page holding special convenience links for sysops
 *
 * @author Yaron Koren
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die;
}

// credits
$GLOBALS['wgExtensionCredits']['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'Admin Links',
	'version' => '0.3.1',
	'author' => 'Yaron Koren',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Admin_Links',
	'descriptionmsg' => 'adminlinks-desc',
	'license-name' => 'GPL-2.0-or-later'
);

$GLOBALS['wgAdminLinksIP'] = __DIR__ . '/';
$GLOBALS['wgMessagesDirs']['AdminLinks'] = __DIR__ . '/i18n';
$GLOBALS['wgExtensionMessagesFiles']['AdminLinksAlias'] =
	$GLOBALS['wgAdminLinksIP'] . 'AdminLinks.alias.php';
$GLOBALS['wgSpecialPages']['AdminLinks'] = 'AdminLinks';
$GLOBALS['wgHooks']['PersonalUrls'][] = 'AdminLinks::addURLToUserLinks';
$GLOBALS['wgAvailableRights'][] = 'adminlinks';
// by default, sysops see the link to this page
$GLOBALS['wgGroupPermissions']['sysop']['adminlinks'] = true;
$GLOBALS['wgAutoloadClasses']['AdminLinks']
	= $GLOBALS['wgAutoloadClasses']['ALTree']
	= $GLOBALS['wgAutoloadClasses']['ALSection']
	= $GLOBALS['wgAutoloadClasses']['ALRow']
	= $GLOBALS['wgAutoloadClasses']['ALItem']
	= $GLOBALS['wgAdminLinksIP'] . 'AdminLinks_body.php';
