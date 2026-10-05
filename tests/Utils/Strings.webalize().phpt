<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Strings::webalize()
 */

use Nette\Utils\Strings;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('ASCII input and slug options', function () {
	Assert::same('', Strings::webalize(''));
	Assert::same('a-b', Strings::webalize(" A\x00\x7F\tB "));
	Assert::same('foo_bar.baz', Strings::webalize(' Foo_Bar.BAZ! ', '_.'));
	Assert::same('Foo_Bar.BAZ', Strings::webalize(' Foo_Bar.BAZ! ', '_.', lower: false));
});


Assert::same(
	'zlutoucky-kun-oeooo',
	Strings::webalize('&ŽLUŤOUČKÝ KŮŇ öőôo!'),
);
Assert::same(
	'ZLUTOUCKY-KUN-oeooo',
	Strings::webalize('&ŽLUŤOUČKÝ KŮŇ öőôo!', lower: false),
);
if (class_exists('Transliterator') && Transliterator::create('Any-Latin; Latin-ASCII')) {
	Assert::same('1-4-!', Strings::webalize("\u{BC} !", '!'));
}

Assert::same('a-b', Strings::webalize("a\u{A0}b")); // non-breaking space
Assert::exception(
	fn() => Strings::webalize("0123456789\xFF"),
	Nette\Utils\RegexpException::class,
	null,
	PREG_BAD_UTF8_ERROR,
);
