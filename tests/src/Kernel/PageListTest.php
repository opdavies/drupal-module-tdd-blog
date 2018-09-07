<?php

namespace Drupal\Tests\tdd_blog\Kernel;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\Tests\node\Traits\NodeCreationTrait;
use Drupal\views\ResultRow;

/**
 * @group tdd_blog
 */
class PageListTest extends EntityKernelTestBase {

  use NodeCreationTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'node',
    'tdd_blog',
    'views',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');

    $this->installConfig(['filter', 'tdd_blog']);
  }

  /**
   * Ensure that only the correct nodes are returned.
   *
   * Ensure that only published pages are returned by the view. Unpublished
   * pages or content of different types should not be shown.
   */
  public function testOnlyPublishedArticlesAreShown() {
    // This is a published article, so it should be visible.
    $this->createNode(['type' => 'page', 'status' => TRUE]);

    // This is a page, so it should not be visible.
    $this->createNode(['type' => 'article']);

    // This article is not published, so it should not be visible.
    $this->createNode(['type' => 'article', 'status' => FALSE]);

    // Rather than testing the rendered HTML, we are going to load the view
    // results programmatically and run assertions against the data it returns.
    // This makes it easier to test certain scenarios, and ensures that the
    // test is future-proofed and won't fail at a later date due to a change in
    // the presentation code.
    $nids = $this->getViewResults();

    // Only node 1 matches the criteria of being a published page, so only that
    // node ID should be being returned from the view. assertEquals() can be
    // used to compare the expected result to what is being returned.
    $this->assertEquals([2], $nids);
  }

  /**
   * Ensure that the results are ordered by title.
   */
  public function testArticlesAreOrderedByDate() {
    $this->createNode(['type' => 'article', 'created' => (new DrupalDateTime('+1 day'))->getTimestamp()]);
    $this->createNode(['type' => 'article', 'created' => (new DrupalDateTime('+1 month'))->getTimestamp()]);
    $this->createNode(['type' => 'article', 'created' => (new DrupalDateTime('+3 days'))->getTimestamp()]);
    $this->createNode(['type' => 'article', 'created' => (new DrupalDateTime('+1 hour'))->getTimestamp()]);

    // Get the result data from the view.
    $nids = $this->getViewResults();

    // Compare the expected order based on the titles defined above to the
    // ordered results from the view.
    $this->assertEquals([4, 1, 3, 2], $nids);
  }

  /**
   * Load the view and get the results.
   *
   * @param string $view
   *   (optional) The name of the view. Defaults to 'blog'.
   *
   * @return array
   *   An array of returned entity IDs.
   */
  private function getViewResults($view = 'blog') {
    return array_map(function (ResultRow $result) {
      return $result->_entity->id();
    }, views_get_view_result($view));
  }

}
