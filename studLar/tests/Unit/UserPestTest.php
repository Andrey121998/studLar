<?php

use App\Models\User;

test('пользователь может быть создан через Pest', function () {
    $userData = [
        'name' => 'John Pest',
        'email' => 'john@pest.com',
        'password' => bcrypt('pest123'),
    ];

    $user = new User($userData);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Pest')
        ->and($user->email)->toBe('john@pest.com')
        ->and(password_verify('pest123', $user->password))->toBeTrue();
});

test('метод getFullName работает через Pest', function () {
    $user = new User(['name' => 'Jane Pest']);

    expect($user->getFullName())->toBe('Jane Pest');
});
