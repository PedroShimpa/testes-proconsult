<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoutesTest extends TestCase
{

    public function testLoginRoute()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'teste@chamados.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function testRegisterRoute()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'cpf' => $this->generateCPF(),
            'email' => 'email_teste_' . rand(1, 2000) . '@mail.com',
            'password' => 'password',

        ]);

        $response->assertStatus(200);
    }

    private function generateCPF()
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);

        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        return "$n1$n2$n3$n4$n5$n6$n7$n8$n9$d1$d2";
    }
}
