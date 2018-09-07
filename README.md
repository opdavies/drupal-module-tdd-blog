# TDD Example Drupal 8 Module for DrupalCamp Dublin

A demo module to accompany my [TDD - Test Driven Drupal][0] talk at DrupalCamp
Dublin 2017.

In order to see my workflow of writing comments first, converting them into
failing tests, and then writing the implementation code to make them pass, you
can see the [list of previous commits][1] and see each step taken, as well as
[the tags][2] that identify the commits when each failing test is added and
then subsequently passes.

## Acceptance Criteria

This module will be used to demonstrate how to take a test-driven approach to
develop a module to the following acceptance criteria:

- As a site visitor
- I want to see a list of all published blog posts at `/blog`
- Ordered by post date, with the newest posts first

## Installation

Within your Drupal 8 site:

```bash
cd modules
git clone git@github.com:opdavies/drupal-module-tdd-blog.git tdd_blog
```

## Running the Tests

These tests are functional tests based on the `BrowserTestBase` class so need
to be executed with PHPUnit (which is required in core's `composer.json` file).
The path to your `vendor` directory may be different depending on your setup.

Because of autoloading, you will either need to be inside Drupal's `core` subdirectory
, or add `-c core` to the PHPUnit command when running the tests for them to execute successfully.

This also assumes that the module is within a `modules/custom` directory and
named `tdd_blog` as per the repository name.

```
vendor/bin/phpunit -c core modules/custom/tdd_blog
```

You can use PHPUnit's `--filter` option to specify a single test method to run,
rather than all of the tests within the module. For example:

```
vendor/bin/phpunit -c core modules/custom/tdd_blog --filter=testOnlyPublishedPagesAreShown
```

[0]: https://www.oliverdavies.uk/talks/tdd-test-driven-drupal
[1]: https://github.com/opdavies/drupal-module-tdd-blog/commits/HEAD
[2]: https://github.com/opdavies/drupal-module-tdd-blog/tags
