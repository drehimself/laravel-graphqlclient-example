<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $client = \Softonic\GraphQL\ClientBuilder::build('http://booksql-laravel.test/graphql');

    $query = '
    mutation createBook($title: String!, $author: String!, $category: Int!) {
      createBook(title: $title, author: $author, category: $category) {
        id
        title
      }
    }';

    $variables = [
        'title' => 'Awesome New Book',
        'author' => 'Andre',
        'category' => 3,
    ];

    $response = $client->query($query, $variables);

    dd($response->getData());
});

Route::get('guzzle', function () {
    $client = new \GuzzleHttp\Client;

    $response = $client->post('http://booksql-laravel.test/graphql', [
        \GuzzleHttp\RequestOptions::JSON => ['query' => 'mutation {createBook(title: "New Book", author: "Andre", category: 1) { id title author }}']
    ]);

    dd(json_decode($response->getBody()->getContents()));
});

Route::get('zttp', function () {
    $response = \Zttp\Zttp::asJson()->post('http://booksql-laravel.test/graphql', [
        'query' => 'query { books { title author }}',
    ]);

    dd($response->json());
});
