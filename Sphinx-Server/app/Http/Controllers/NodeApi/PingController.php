<?php

namespace App\Http\Controllers\NodeApi;

use App\Facades\SphinxNode;
use App\Http\Controllers\Controller;

class PingController extends Controller
{
    public function ping()
    {
        return 'result:' . json_encode(SphinxNode::ping());
    }
}