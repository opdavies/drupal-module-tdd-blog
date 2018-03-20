<?php

namespace Drupal\Tests\tdd_dublin\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

/**
 * @group tdd_dublin
 */
class PageListTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'system',
    'tdd_dublin',
    'tdd_dublin_test',
    'user',
    'views',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['tdd_dublin']);

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
  }

  /**
   * Ensure that only the correct nodes are returned.
   *
   * Ensure that only published pages are returned by the view. Unpublished
   * pages or content of different types should not be shown.
   */
  public function testOnlyPublishedPagesAreShown() {
    NodeType::create(['type' => 'article', 'name' => t('Article')]);

    // This is a published page, so it should be visible.
    Node::create($this->getValidParams(['status' => TRUE]))->save();

    // This is an article, so it should not be visible.
    Node::create($this->getValidParams(['type' => 'article']))->save();

    // This page is not published, so it should not be visible.
    Node::create($this->getValidParams(['status' => FALSE]))->save();

    // Rather than testing the rendered HTML, we are going to load the view
    // results programmatically and run assertions against the data it returns.
    // This makes it easier to test certain scenarios, and ensures that the
    // test is future-proofed and won't fail at a later date due to a change in
    // the presentation code.
    $nids = [];
    foreach (views_get_view_result('pages') as $result) {
      $nids[] = $result->nid;
    }

    // Only node 1 matches the criteria of being a published page, so only that
    // node ID should be being returned from the view. assertEquals() can be
    // used to compare the expected result to what is being returned.
    $this->assertEquals([1], $nids);
  }

  /**
   * Ensure that the results are ordered by title.
   */
  public function testResultsAreOrderedAlphabetically() {
    // Create a number of nodes with different titles, specifying the title for
    // each. These are intentionally not in alphabetical order so that when the
    // assertion is written for the results to be in the expected order, it
    // will fail, rather than them being in the expected order based on the
    // default sort criteria based on the created timestamp.
    //
    // Also, the titles are added explicitly so that the assertion can be
    // written against the expected order based on these titles. If they
    // weren't added, each title would be automatically generated so the
    // expected order would not be known beforehand.
    Node::create($this->getValidParams(['title' => 'Page A']))->save();
    Node::create($this->getValidParams(['title' => 'Page D']))->save();
    Node::create($this->getValidParams(['title' => 'Page C']))->save();
    Node::create($this->getValidParams(['title' => 'Page B']))->save();

    // Get the result data from the view.
    $nids = [];
    foreach (views_get_view_result('pages') as $result) {
      $nids[] = $result->nid;
    }

    // Compare the expected order based on the titles defined above to the
    // ordered results from the view.
    $this->assertEquals([1, 4, 3, 2], $nids);
  }

  /**
   * Default parameters for creating test nodes.
   *
   * @param array $overrides
   *   An associative array of overridden values.
   *
   * @return array
   *   The overridden parameters array, combined with the defaults.
   */
  private function getValidParams(array $overrides = []) {
    return array_merge([
      'status' => TRUE,
      'title' => $this->randomString(),
      'type' => 'page',
    ], $overrides);
  }

}
