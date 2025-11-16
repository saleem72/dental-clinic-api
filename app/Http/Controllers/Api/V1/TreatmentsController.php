<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\DentalProcedure;
use Illuminate\Http\Request;

class TreatmentsController extends Controller
{
    public function getDentalProcedures() {
      $procedures =  DentalProcedure::all();
      return apiResponse($procedures);
    }
}
