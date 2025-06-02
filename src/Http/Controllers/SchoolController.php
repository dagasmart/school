<?php

namespace Biz\School\Http\Controllers;

use DagaSmart\BizAdmin\Controllers\AdminController;

class SchoolController extends AdminController
{
    public function index()
    {
        $page = $this->basePage()->body('School Extension.');

        return $this->response()->success($page);
    }
}
