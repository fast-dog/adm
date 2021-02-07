<?php

namespace FastDog\Adm\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\View\View;

/**
 * Class AdminController
 *
 * @package FastDog\Adm\Http\Controllers
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class AdminController extends BaseController
{

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('FastDog\Adm\Http\Middleware\Admin')->except(['getLogin', 'postLogin']);
    }

    /**
     * @return View
     */
    public function getIndex(): View
    {
        return view('admin::admin.dashboard');
    }
}
