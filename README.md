# TDD Dublin demo module

A demo module to accompany my [TDD - Test Driven Drupal][0] talk at DrupalCamp
Dublin 2017.

## Acceptance Criteria

This module will be used to demonstrate how to take a test-driven approach to
develop a module to the following acceptance criteria:

- As a site visitor
- I want to see a list of all published pages at `/pages`
- Ordered alphabetically by title

## Running the Tests

These tests are functional tests based on the `BrowserTestBase` class so need
to be executed with PHPUnit (which is required in core's `composer.json` file).
The path to your `vendor` directory may be different depending on your setup.

Because of autoloading, you will need to be inside Drupal's `core` subdirectory
when running the tests for them to execute successfully.

This also assumes that the module is within a `modules/custom` directory and
named `tdd_dublin` as per the repository name.

```
cd core

../vendor/bin/phpunit ../modules/custom/tdd_dublin
```

You can use PHPUnit's `--filter` option to specify a single test method to run,
rather than all of the tests within the module. For example:

```
../vendor/bin/phpunit ../modules/custom/tdd_dublin --filter=testOnlyPublishedPagesAreShown
```

[0]: https://www.oliverdavies.uk/talks/tdd-test-driven-drupal
