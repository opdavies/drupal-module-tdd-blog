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

}
