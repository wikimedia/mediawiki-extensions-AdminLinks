<?php

use MediaWiki\SpecialPage\SpecialPage;

/**
 * Hook handler class
 */
class AdminLinksHooks implements
	\MediaWiki\Hook\SkinTemplateNavigation__UniversalHook
{

	/**
	 * For administrators, add a link to the special 'AdminLinks' page
	 * among the user's "personal URLs" at the top, if they have
	 * the 'adminlinks' permission.
	 *
	 * @param SkinTemplate $skinTemplate
	 * @param array &$links
	 */
	public function onSkinTemplateNavigation__Universal( $skinTemplate, &$links ): void {
		if ( !$skinTemplate->getUser()->isAllowed( 'adminlinks' ) ) {
			return;
		}
		// If skin lacks the "user-menu" key, just exit.
		// @todo Add support, if possible, for skins that lack
		// this key, like Chameleon.
		if ( !array_key_exists( 'user-menu', $links ) ) {
			return;
		}

		$al = SpecialPage::getTitleFor( 'AdminLinks' );
		$out = $skinTemplate->getOutput();
		$out->addModules( [ 'ext.adminlinks' ] );
		$href = $al->getLocalURL();
		$adminLinksVals = [
			'text' => $skinTemplate->msg( 'adminlinks' )->text(),
			'href' => $href,
			'active' => ( $href === $skinTemplate->getTitle()->getLocalURL() ),
			'icon' => 'link'
		];

		// Find the location of the 'my preferences' link, and
		// add the link to 'AdminLinks' right before it.
		// This is a "key-safe" splice - it preserves both the
		// keys and the values of the array, by editing them
		// separately and then rebuilding the array.
		// Based on the example at http://us2.php.net/manual/en/function.array-splice.php#31234
		$menuKeys = array_keys( $links['user-menu'] );
		$menuValues = array_values( $links['user-menu'] );
		$prefsLocation = array_search( 'preferences', $menuKeys );
		array_splice( $menuKeys, $prefsLocation, 0, 'adminlinks' );
		array_splice( $menuValues, $prefsLocation, 0, [ $adminLinksVals ] );

		$links['user-menu'] = [];
		for ( $i = 0; $i < count( $menuKeys ); $i++ ) {
			$links['user-menu'][$menuKeys[$i]] = $menuValues[$i];
		}
	}

}
