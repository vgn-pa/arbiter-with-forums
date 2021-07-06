<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\IntelParser;

class IntelParserController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IntelParser $parser)
    {
        //$url = $request->input('xml');
        $xml = null;

    }

    public function store(Request $request, IntelParser $parser)
    {
        //dd($parser->parse($request->input('xml')));
        try {
            return $parser->parse($request->input('xml'));
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

    }
}
