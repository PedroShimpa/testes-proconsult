<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChamadosRoutesTest extends TestCase
{

    /** @test */
    public function testStoreChamadoRoute()
    {
        $response = $this->postJson('/api/chamados', [
            'title' => 'New Chamado',
            'description' => 'Description of the chamado'
        ]);

        $response->assertStatus(401); 
    }

    /** @test */
    public function testGetChamadosRoute()
    {
        $response = $this->getJson('/api/chamados');

        $response->assertStatus(401); 
    }

    /** @test */
    public function testPostChamadosReplyRoute()
    {
        $response = $this->postJson('/api/chamados/reply/1');
        $response->assertStatus(401); 
    }
    
    /** @test */
    public function testPostChamadosFinishRoute()
    {
        $response = $this->putJson('/api/chamados/finish/1');
        $response->assertStatus(401); 
    }

    /** @test */
    public function testGetChamadoByIdRoute()
    {
        $response = $this->getJson('/api/chamados/1');
        $response->assertStatus(401); 
    }
}
