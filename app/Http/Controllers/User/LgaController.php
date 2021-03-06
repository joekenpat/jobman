<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\LgaSearch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LgaController extends Controller
{
  public function index(Request $request)
  {
    $lgas = LgaSearch::apply($request, 20);
    $response['status'] = 'success';
    $response['lgas'] = $lgas;
    return response()->json($response, Response::HTTP_OK);
  }
}
