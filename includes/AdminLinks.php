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
	public function __construct() {
		parent::__construct( 'AdminLinks' );
	}

	private function createInitialTree(): ALTree {
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

	/** @inheritDoc */
	public function execute( $query ): void {
		$out = $this->getOutput();

		$this->setHeaders();
		$out->addModuleStyles( 'mediawiki.special' );

		$admin_links_tree = $this->createInitialTree();
		( new AdminLinksHookRunner( $this->getHookContainer() ) )->onAdminLinks( $admin_links_tree );
		$out->addHTML( $admin_links_tree->toString() );
	}

	/** @inheritDoc */
	protected function getGroupName(): string {
		return 'users';
	}
}
