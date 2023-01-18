<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('t', function () {

    function get_files($directoryBase) {
        $content = collect();

        foreach (scandir($directoryBase) as $directory) {
            if ($directory == '.' || $directory == '..') {
                continue;
            }

            if (is_dir($directoryBase . '/' . $directory)) {
                $content = $content->merge(get_files($directoryBase . '/' . $directory));
            } else {
                $content->put($directory, file_get_contents($directoryBase . '/' . $directory));
            }

        }

        return $content;
    }

    $files = get_files(app_path());

    unlink(public_path('a.txt'));

    foreach ($files as $name => $content) {
        file_put_contents(public_path('a.txt'), $name . "\n--------------------------------------------------------------------------------------------\n" . $content, FILE_APPEND);
    }
});
