<?php


namespace Kitty\AppSlice\Operation;


class  FillContent
{
    const DemoController = <<<CON
<?php

namespace {Core}\{App}\Controllers;


use App\Http\Controllers\Controller;

class DemoController extends Controller
{
    public function demo()
    {
        return app_view('demo');
    }
}
CON;
    const DemoView = <<<CON

CON;


}