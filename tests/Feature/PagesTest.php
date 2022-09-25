<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_the_application_main_page_redirect_response()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
