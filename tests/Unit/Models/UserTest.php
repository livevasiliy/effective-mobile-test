<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(User::class)]
class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        // Arrange & Act
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        // Assert
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_email_must_be_unique(): void
    {
        // Arrange
        User::factory()->create(['email' => 'john@example.com']);

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => 'john@example.com']);
    }

    public function test_password_is_hashed(): void
    {
        // Arrange & Act
        $user = User::factory()->create(['password' => 'password123']);

        // Assert
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
