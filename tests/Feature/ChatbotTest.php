<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_faq_matching()
    {
        // Buat FAQ test
        Faq::create([
            'pertanyaan' => 'hallo',
            'jawaban' => 'selamat datang',
            'kategori' => 'umum',
            'is_aktif' => 1,
        ]);

        // Test request
        $response = $this->postJson('/dashboard/chatbot/message', [
            'message' => 'halo'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'reply' => 'selamat datang'
                 ]);
    }
}