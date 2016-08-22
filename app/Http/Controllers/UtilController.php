<?php

namespace App\Http\Controllers;

use Config;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Abhayagiri\DB;

class UtilController extends Controller
{
    public function version(Request $request)
    {
        $data = [
            'Abhayagiri\\getGitVersion()' => \Abhayagiri\getGitVersion(),
            'base_path()' => base_path(),
            'realpath(base_path())' => realpath(base_path()),
            'ini_get(\'opcache.use_cwd\')' => ini_get('opcache.use_cwd'),
            'ini_get(\'opcache.revalidate_path\')' => ini_get('opcache.revalidate_path'),
            'ini_get(\'opcache.revalidate_freq\')' => ini_get('opcache.revalidate_freq'),
        ];

        ob_start();
        dump($data);
        $output = ob_get_clean();
        return $output;
    }

    public function mahapanelBypass(Request $request)
    {
        if (Config::get('abhayagiri.auth.mahapanel_bypass')) {
            $mahaguildId = DB::getDB()->login($request->input('email'));
            if ($mahaguildId) {
                $request->session()->put('mahaguild_id', $mahaguildId);
                return redirect('/mahapanel');
            }
        }
        return redirect('/');
    }
}
