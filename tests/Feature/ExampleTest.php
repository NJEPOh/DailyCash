<?php

test('opening root url as guest redirects to login page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
