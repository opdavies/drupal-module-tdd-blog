<?php

namespace Drupal\Tests\tdd_dublin\Functional;

use Drupal\Tests\BrowserTestBase;

class PageListTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['tdd_dublin'];

  /**
   * Test that the pages listing page exists and is accessible.
   */
  public function testListingPageExists() {
    // Go to /pages and check that it is accessible by checking the status
    // code.
    $this->drupalGet('pages');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Ensure that only the correct nodes are returned.
   *
   * Ensure that only published pages are returned by the view. Unpublished
   * pages or content of different types should not be shown.
   */
  public function testOnlyPublishedPagesAreShown() {
    $this->drupalCreateContentType(['type' => 'article']);

    // This is a published page, so it should be visible.
    $this->drupalCreateNode(['type' => 'page', 'status' => TRUE]);

    // This is an article, so it should not be visible.
    $this->drupalCreateNode(['type' => 'article']);

    // This page is not published, so it should not be visible.
    $this->drupalCreateNode(['type' => 'page', 'status' => FALSE]);

    // Rather than testing the rendered HTML, we are going to load the view
    // results programmatically and run assertions against the data it returns.
    // This makes it easier to test certain scenarios, and ensures that the
    // test is future-proofed and won't fail at a later date due to a change in
    // the presentation code.
    $result = views_get_view_result('pages');

    // $result contains an array of Drupal\views\ResultRow objects. We can use
    // array_column to get the nid from each node and return them as an array.
    $nids = array_column($result, 'nid');

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
    $this->drupalCreateNode(['title' => 'Page A']);
    $this->drupalCreateNode(['title' => 'Page D']);
    $this->drupalCreateNode(['title' => 'Page C']);
    $this->drupalCreateNode(['title' => 'Page B']);

    // Get the result data from the view.
    $nids = array_column(views_get_view_result('pages'), 'nid');

    // Compare the expected order based on the titles defined above to the
    // ordered results from the view.
    $this->assertEquals([1, 4, 3, 2], $nids);
  }

}
