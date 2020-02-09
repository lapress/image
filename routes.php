<?php

Route::get(config('images.route'), 'ImagesController@show')
    ->namespace('LaPress\Image')
    ->where('path', '.*');
