<?php

use MediaWiki\HookContainer\HookContainer;

/**
 * This is a hook runner class, see docs/Hooks.md in core.
 * @internal
 */
class AdminLinksHookRunner implements
	AdminLinksHook
{
	private HookContainer $hookContainer;

	public function __construct( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
	}

	/**
	 * @inheritDoc
	 */
	public function onAdminLinks( ALTree &$admin_links_tree ) {
		return $this->hookContainer->run(
			'AdminLinks',
			[ &$admin_links_tree ]
		);
	}
}
