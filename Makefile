.PHONY: default
default: \
	code \
	test

# -----
# Testing

test: \
	test.unit

test.unit:
	vendor/bin/phpunit --group=unit

test.coverage:
	docker-compose run php-cli sh -c "vendor/bin/phpunit --group=unit --coverage-html=build/coverage"

# ------
# Code Control

code: \
	code.format \
	code.sniff.report \
	code.phpstan

code.fix: \
	code.format \
	code.sniff.fix \
	code.sniff.report \
	code.phpstan

code.format:
	vendor/bin/php-formatter formatter:header:fix src --ansi --config="build/code/config"
	vendor/bin/php-formatter formatter:header:fix tests --ansi --config="build/code/config"
	vendor/bin/php-formatter formatter:use:sort src --ansi --config="build/code/config"
	vendor/bin/php-formatter formatter:use:sort tests --ansi --config="build/code/config"

code.sniff.report:
	vendor/bin/phpcs --report=diff --report-full

code.sniff.report.only.full:
	vendor/bin/phpcs --report-full

code.sniff.report.only.diff:
	vendor/bin/phpcs --report=diff

code.sniff.fix:
	vendor/bin/phpcbf

code.phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon
