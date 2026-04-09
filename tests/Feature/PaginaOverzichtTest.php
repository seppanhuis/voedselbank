<?php

use App\Models\User;

function directieGebruiker(): User
{
    $user = new User([
        'name' => 'Test Directie',
        'email' => 'directie.test@example.com',
        'password' => 'password',
        'role' => User::ROLE_DIRECTIE,
    ]);

    $user->id = 1;

    return $user;
}

test('klant overzicht pagina laadt met test empty', function () {
    $this->actingAs(directieGebruiker());

    $response = $this->get(route('klant.index', ['test' => 'empty']));

    $response->assertOk();
});

test('leverancier overzicht pagina laadt voor directie met test empty', function () {
    $this->actingAs(directieGebruiker());

    $response = $this->get(route('leverancier.index', ['test' => 'empty']));

    $response->assertOk();
});

test('voorraad overzicht pagina laadt voor directie met test empty', function () {
    $this->actingAs(directieGebruiker());

    $response = $this->get(route('voorraad.index', ['test' => 'empty']));

    $response->assertOk();
});

test('voedselpakket overzicht pagina laadt voor directie met test empty', function () {
    $this->actingAs(directieGebruiker());

    $response = $this->get(route('voedselpakket.index', ['test' => 'empty']));

    $response->assertOk();
});
