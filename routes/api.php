<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* Authentication */
Route::post('auth', 'Auth@add');
Route::get('auth', 'Auth@get_by_id');
Route::get('auth/AllUsersCount', 'Auth@getAllCount');


/* MercadoPago */
Route::post('createPreference','MercadoPago@createPreference');
Route::post('notification', 'MercadoPago@notificationIPN');
Route::post('pr', 'MercadoPago@pr');
Route::post('createShipping', 'MercadoPago@createShippment');
Route::get('getAllOrders', 'MercadoPago@get_all_merchant_order');
Route::get('getToken', 'MercadoPago@getToken');
Route::get('getAllOrdersFromMEPA', 'MercadoPago@get_order_by_mepa');
Route::post('getOrdersById', 'MercadoPago@get_orders_by_user');


/* Carro */
Route::get('cart', 'Cart@getAll');
Route::post('cart', 'Cart@add');
Route::delete('cart/{id}', 'Cart@delete');


/* Categorias */
Route::get('categories', 'Categories@principal');
Route::get('categories/all', 'Categories@getAll');
Route::get('categories/sub/{id}', 'Categories@subCategories');
Route::post('categories', 'Categories@add');
Route::delete('categories/{id}', 'Categories@delete');


/* Productos */
Route::get('products', 'Products@getAll');
Route::post('products', 'Products@add');
Route::post('products/addImage', 'Products@addImage');
Route::delete('products/{id}', 'Products@delete');
Route::get('products/search/{value}', 'Products@search');
Route::get('products/agotados', 'Products@agotados');
Route::get('products/categorie/{id}', 'Products@productById');
Route::get('products/news', 'Products@news');


/* Favoritos */
Route::get('favorites', 'Favorites@getAll');
Route::post('favorites', 'Favorites@add');


/* Locations */
Route::get('UserLocations', 'UserLocations@getAll');
Route::post('UserLocations', 'UserLocations@add');

/* Ventas */
Route::get('sales', 'Seller@getAll');
Route::post('salesAdd', 'Seller@add');
Route::delete('sales/{id}', 'Seller@delete');
Route::post('salesUpdate', 'Seller@update');
Route::post('salesUpdateStatus', 'Seller@updateStatus');
