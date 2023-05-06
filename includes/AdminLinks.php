<?php
/**
 * Class for the page Special:AdminLinks
 *
 * @author Yaron Koren
 */

class AdminLinks extends SpecialPage {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( 'AdminLinks' );
	}

	function createInitialTree() {
		$tree = new ALTree();

		// 'general' section
		$general_section = new ALSection( $this->msg( 'adminlinks_general' )->text() );
		$main_row = new ALRow( 'main' );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Statistics' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Version' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Specialpages' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Log' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Allmessages' ) );
		$main_row->addItem( ALItem::newFromEditLink(
			'Sidebar',
			$this->msg( 'adminlinks_editsidebar' )->text()
		) );
		$main_row->addItem( ALItem::newFromEditLink(
			'Common.css',
			$this->msg( 'adminlinks_editcss' )->text()
		) );
		$main_row->addItem( ALItem::newFromEditLink(
			'Mainpage',
			$this->msg( 'adminlinks_editmainpagename' )->text()
		) );
		$general_section->addRow( $main_row );
		$tree->addSection( $general_section );

		// 'users' section
		$users_section = new ALSection( $this->msg( 'adminlinks_users' )->text() );
		$main_row = new ALRow( 'main' );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Listusers' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'CreateAccount' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Userrights' ) );
		$users_section->addRow( $main_row );
		$tree->addSection( $users_section );

		// 'browsing and searching' section
		$browse_search_section = new ALSection( $this->msg( 'adminlinks_browsesearch' )->text() );
		$main_row = new ALRow( 'main' );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Allpages' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Listfiles' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Search' ) );
		$browse_search_section->addRow( $main_row );
		$tree->addSection( $browse_search_section );

		// 'importing and exporting' section
		$import_export_section = new ALSection( $this->msg( 'adminlinks_importexport' )->text() );
		$main_row = new ALRow( 'main' );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Export' ) );
		$main_row->addItem( ALItem::newFromSpecialPage( 'Import' ) );
		$import_export_section->addRow( $main_row );
		$tree->addSection( $import_export_section );

		return $tree;
	}

	function execute( $query ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$out->addModuleStyles( 'mediawiki.special' );

		$admin_links_tree = $this->createInitialTree();
		$this->getHookContainer()->run( 'AdminLinks', [ &$admin_links_tree ] );
		$out->addHTML( $admin_links_tree->toString() );
	}

	/**
	 * For administrators, add a link to the special 'AdminLinks' page
	 * among the user's "personal URLs" at the top, if they have
	 * the 'adminlinks' permission.
	 *
	 * @param SkinTemplate $skinTemplate
	 * @param array &$links
	 */
	public static function addURLToUserLinks(
		$skinTemplate,
		&$links
	): void {
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
		$href = $al->getLocalURL();
		$adminLinksVals = [
			'text' => $skinTemplate->msg( 'adminlinks' )->text(),
			'href' => $href,
			'active' => ( $href == $skinTemplate->getTitle()->getLocalURL() )
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}
}
