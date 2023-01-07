<?php

namespace MainaDavid\MultiShortcodeMpesa\Tests\Feature;

use Tests\TestCase;

class b2cTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function can_send_b2c()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}