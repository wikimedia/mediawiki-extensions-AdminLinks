<?php

/**
 * This is a hook handler interface, see docs/Hooks.md in core.
 * Use the hook name "AdminLinks" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AdminLinksHook {
	/**
	 * @param ALTree &$admin_links_tree
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAdminLinks( ALTree &$admin_links_tree );
}
