<?php

$mahaguildId = Session::get('mahaguild_id');

if ($mahaguildId) {
    $currentUser = DB::table('mahaguild')
        ->where('id', '=', $mahaguildId)
        ->first();
} else {
    $currentUser = null;
}

if (!$currentUser) {
    App\Util::redirect('/mahapanel/login');
}
