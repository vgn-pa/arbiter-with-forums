<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\ScanParser;

class ParserController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ScanParser $parser)
    {
        return $parser->parse($request->input('url'));
    }

    public function store(Request $request, ScanParser $parser)
    {
        return $parser->parse($request->input('url'));
    }


}
