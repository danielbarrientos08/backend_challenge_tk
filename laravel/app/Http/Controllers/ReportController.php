<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\UsersReport;
use App\Models\Report;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use App\Http\Requests\ReportGenerateRequest;

class ReportController extends Controller
{
    public function generateReport(ReportGenerateRequest $request)
    {
        $file_name =  $request->title.'_'.time().'.xlsx';

        $report  = new Report();
        $report->title       = $request->title;
        $report->created_at  = Carbon::now();
        $report->report_link = $file_name;
        $report->save();

        // Excel::store((new UsersReport($request->start_date,$request->end_date)), $file_name, 'public');
        
         (new UsersReport($request->start_date,$request->end_date))->store( $file_name, 'public');
        
        return response()->json([
            'status'=>'success',
            'message' => 'El reporte se esta generando.'
        ],200);
    }
}
