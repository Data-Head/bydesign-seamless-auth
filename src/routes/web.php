<?php

Route::get(config('bydesign-seamless-auth.auth_route') . '/{guid}', 'DataHead\ByDesignSeamlessAuth\Http\Controllers\ByDesignAuthController@checkGUID');
