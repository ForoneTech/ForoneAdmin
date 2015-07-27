<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:30
 * Email: smartydroid@gmail.com
 */

Route::get('/', function(){
    return redirect('/admin/auth/login');
});
Route::controllers([
    'admin/auth' => 'Forone\Admin\Controllers\Auth\AuthController',
]);