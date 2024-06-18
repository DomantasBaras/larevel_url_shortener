<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Url;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_shorten_a_url_without_prefix()
    {
        $response = $this->post('/shorten', [
            'original_url' => 'https://example.com'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['short_url']);
    }

    /** @test */
    public function it_can_shorten_a_url_with_prefix()
    {
        $response = $this->post('/shorten', [
            'original_url' => 'https://example.com',
            'prefix' => 'custom'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['short_url']);
    }

    /** @test */
    public function it_can_redirect_to_the_original_url_without_prefix()
    {
        Url::create([
            'original_url' => 'https://example.com',
            'short_hash' => 'abc123'
        ]);

        $response = $this->get('/abc123');

        $response->assertRedirect('https://example.com');
    }

    /** @test */
    public function it_can_redirect_to_the_original_url_with_prefix()
    {
        $url = Url::create([
            'original_url' => 'https://example.com',
            'short_hash' => 'abc123',
            'prefix' => 'custom'
        ]);

        $this->assertDatabaseHas('urls', [
            'original_url' => 'https://example.com',
            'short_hash' => 'abc123',
            'prefix' => 'custom'
        ]);

        $response = $this->get('custom/abc123');

        $response->assertStatus(302);

        $response->assertRedirect('https://example.com');
    }

}
