<?php

namespace App\Http\Controllers;

use App\Legacy;
use Illuminate\Support\Facades\Lang;

class LegacyController extends Controller
{
    public function ajax()
    {
        return $this->response('php/ajax.php', false);
    }

    public function bookIndex()
    {
        return $this->response('index.php', 'books');
    }

    public function bookShow($id)
    {
        return $this->response('index.php', 'books/' . $id);
    }

    public function datatables()
    {
        return $this->response('php/datatables.php', false);
    }

    public function calendar()
    {
        return $this->response('index.php', 'calendar');
    }

    public function home()
    {
        return $this->response('index.php', '');
    }

    public function newsIndex()
    {
        return $this->response('index.php', 'news');
    }

    public function newsShow($id)
    {
        return $this->response('index.php', 'news/' . $id);
    }

    public function reflectionIndex()
    {
        return $this->response('index.php', 'reflections');
    }

    public function reflectionShow($id)
    {
        return $this->response('index.php', 'reflections/' . $id);
    }

    protected function response($phpFile, $page)
    {
        $prefix = Lang::getLocale() === 'th' ? 'th/' : '';
        return Legacy::response($prefix . $phpFile, $page);
    }
}
