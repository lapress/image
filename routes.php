<?php

Route::get(config('images.route'), 'LaPress\Image\ImagesController@show')
    ->where('path', '.*');
