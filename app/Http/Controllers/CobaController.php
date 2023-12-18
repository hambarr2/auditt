<?php
namespace App\Http\Controllers;

use App\Models\SPT;
use Illuminate\Http\Request;


class CobaController extends Controller
{
    public function lihatspt($id)
    {
        $spt = SPT::find($id);

        if($spt)
        {
            return response()->json([
                'status'=>200,
                'spt'=>$spt
            ]);
        }
    }
}