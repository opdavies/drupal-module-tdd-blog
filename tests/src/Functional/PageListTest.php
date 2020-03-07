<?php

namespace Drupal\Tests\tdd_blog\Functional;

use Drupal\Tests\BrowserTestBase;
use Symfony\Component\HttpFoundation\Response;

class PageListTest extends BrowserTestBase {

  protected static $modules = ['tdd_blog'];

  protected $defaultTheme = 'stark';

  public function testBlogPageExists() {
    $this->drupalGet('blog');

    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
  }

}
