<?php

use MediaWiki\Tests\HookContainer\HookRunnerTestBase;

/**
 * @covers \AdminLinksHookRunner
 */
class HookRunnerTest extends HookRunnerTestBase {

	public static function provideHookRunners() {
		yield AdminLinksHookRunner::class => [ AdminLinksHookRunner::class ];
	}
}
