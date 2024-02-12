<?php

use MediaWiki\Tests\HookContainer\HookRunnerTestBase;

/**
 * @covers \AdminLinksHookRunner
 */
class AdminLinksHookRunnerTest extends HookRunnerTestBase {

	public static function provideHookRunners() {
		yield AdminLinksHookRunner::class => [ AdminLinksHookRunner::class ];
	}
}
