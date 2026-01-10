<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Interfaces\UserRepositoryInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class MockTest extends TestCase
{
    // Добавляем трейт для правильной интеграции Mockery с PHPUnit
    use MockeryPHPUnitIntegration;

    /**
     * Тест 1: Проверяем метод getAll() через мок
     * @test
     */
    public function test_user_repository_mock_with_get_all()
    {
        $mockRepository = Mockery::mock(UserRepositoryInterface::class);

        $mockRepository->shouldReceive('getAll')
            ->once() // Метод должен быть вызван ровно 1 раз
            ->andReturn(collect([
                ['id' => 1, 'name' => 'Mock User 1', 'email' => 'mock1@example.com'],
                ['id' => 2, 'name' => 'Mock User 2', 'email' => 'mock2@example.com'],
                ['id' => 3, 'name' => 'Mock User 3', 'email' => 'mock3@example.com'],
            ]));

        $userService = new UserService($mockRepository);

        $users = $userService->getUsers();

        $this->assertCount(3, $users);
        $this->assertEquals('Mock User 1', $users[0]['name']);
        $this->assertEquals('mock2@example.com', $users[1]['email']);
        $this->assertEquals(3, $users[2]['id']);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $users);

        echo "\n✓ Метод getAll() успешно протестирован через мок";
    }

    /**
     * Тест 2: Проверяем метод find() через мок
     * @test
     */
    public function test_find_user_by_id_mock()
    {
        $mockRepository = Mockery::mock(UserRepositoryInterface::class);

        $mockRepository->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn([
                'id' => 1,
                'name' => 'Mock User For Find',
                'email' => 'find@example.com'
            ]);

        $userService = new UserService($mockRepository);

        $user = $userService->getUserById(1);

        $this->assertEquals('find@example.com', $user['email']);
        $this->assertEquals('Mock User For Find', $user['name']);
        $this->assertEquals(1, $user['id']);

        echo "\n✓ Метод find() успешно протестирован через мок";
    }

    /**
     * Тест 3: Проверяем метод findUserByEmail() через мок
     * ОСНОВНОЙ ТЕСТ ДЛЯ ЗАДАНИЯ 6
     * @test
     */
    public function test_find_user_by_email_mock()
    {
        $mockRepository = Mockery::mock(UserRepositoryInterface::class);

        $testEmail = 'test.email@example.com';

        $mockRepository->shouldReceive('findUserByEmail')
            ->with($testEmail)
            ->once()
            ->andReturn([
                'id' => 50,
                'name' => 'Email User',
                'email' => $testEmail,
                'created_at' => '2024-01-15 10:00:00'
            ]);

        $userService = new UserService($mockRepository);

        $user = $userService->getUserByEmail($testEmail);

        $this->assertEquals($testEmail, $user['email']);
        $this->assertEquals('Email User', $user['name']);
        $this->assertEquals(50, $user['id']);
        $this->assertArrayHasKey('created_at', $user);

        echo "\n✓ Метод findUserByEmail() успешно протестирован через мок (ЗАДАНИЕ 6)";
    }

    /**
     * Тест 4: Проверяем, что метод не вызывается с неправильными данными
     * @test
     */
    public function test_method_not_called_with_wrong_email()
    {
        $mockRepository = Mockery::mock(UserRepositoryInterface::class);

        $mockRepository->shouldReceive('findUserByEmail')
            ->with('correct@email.com')
            ->once()
            ->andReturn(['id' => 1, 'email' => 'correct@email.com']);

        $userService = new UserService($mockRepository);

        $user1 = $userService->getUserByEmail('correct@email.com');
        $this->assertEquals('correct@email.com', $user1['email']);

        echo "\n✓ Проверка вызова метода с правильными параметрами выполнена";
    }

    /**
     * Тест 5: Мок с несколькими вызовами
     * @test
     */
    public function test_multiple_calls_to_find_user_by_email()
    {
        $mockRepository = Mockery::mock(UserRepositoryInterface::class);

        $mockRepository->shouldReceive('findUserByEmail')
            ->with('user1@test.com')
            ->once()
            ->andReturn(['id' => 1, 'email' => 'user1@test.com', 'name' => 'User One']);

        $mockRepository->shouldReceive('findUserByEmail')
            ->with('user2@test.com')
            ->once()
            ->andReturn(['id' => 2, 'email' => 'user2@test.com', 'name' => 'User Two']);

        $userService = new UserService($mockRepository);

        $user1 = $userService->getUserByEmail('user1@test.com');
        $this->assertEquals('User One', $user1['name']);

        $user2 = $userService->getUserByEmail('user2@test.com');
        $this->assertEquals('User Two', $user2['name']);

        echo "\n✓ Множественные вызовы findUserByEmail() протестированы";
    }

    /**
     * Тест 6: Интеграционный тест - реальный репозиторий + мок для зависимости
     * @test
     */
    public function test_integration_with_real_repository()
    {


        $realRepository = new UserRepository();

        $this->assertTrue(method_exists($realRepository, 'findUserByEmail'));

        $result = $realRepository->findUserByEmail('nonexistent@email.com');
        $this->assertNull($result);

        echo "\n✓ Реальный репозиторий корректно реализует интерфейс";
    }

    /**
     * Тест 7: Проверяем мок с помощью PHPUnit встроенных моков (альтернатива Mockery)
     * @test
     */
    public function test_with_phpunit_mock()
    {
        $mockRepository = $this->createMock(UserRepositoryInterface::class);

        $mockRepository->method('findUserByEmail')
            ->with('phpunit@test.com')
            ->willReturn(['id' => 99, 'email' => 'phpunit@test.com', 'name' => 'PHPUnit User']);

        $userService = new UserService($mockRepository);
        $user = $userService->getUserByEmail('phpunit@test.com');

        $this->assertEquals('PHPUnit User', $user['name']);
        $this->assertEquals(99, $user['id']);

        echo "\n✓ Мок через PHPUnit createMock() работает";
    }

}
