<?php


namespace Transave\ScolaCbt\Tests\Feature\Auth;


use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class LoginTest extends TestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'sampledata@test.com',
            'password' => bcrypt('sample1234'),
        ]);
    }

    /** @test */
    public function can_login_user_successfully()
    {
        $loginData = ['email' => 'sampledata@test.com', 'password' => 'sample1234'];
        Sanctum::actingAs($this->user);

        $response = $this->json('POST', 'cbt/login', $loginData, ['Accept' => 'application/json']);
        $response->assertStatus(200)->assertJsonStructure(["success", "message", "data"]);

        $this->assertAuthenticated();
    }

    /** @test */
    public function can_show_errors_when_credentials_do_not_match()
    {
        $credentials = ['email' => 'fakedata@gmail.com', 'password' => 'sample1234'];
        Sanctum::actingAs($this->user);
        $response = $this->json('POST', 'cbt/login', $credentials, ['Accept' => 'application/json']);
        $response->assertStatus(401);
        $this->assertEquals($response['message'], 'authentication failed');
    }

    /** @test */
    public function can_get_authenticated_user_details()
    {
        Sanctum::actingAs($this->user);
        $response = $this->json('GET', 'cbt/user');
        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertEquals(true, $response['success']);
    }

    /** @test */
    public function can_logout_authenticated_user()
    {
        Sanctum::actingAs($this->user);
        $response = $this->json('POST', route('cbt.logout'), []);
        $response->assertStatus(200);
    }
}