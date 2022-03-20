<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\UsersReport;
use App\Models\Report;
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
        
         (new UsersReport($request->start_date,$request->end_date))->store( $file_name, 'public');
        
        return response()->json([
            'status'=>'success',
            'message' => 'El reporte se esta generando.'
        ],201);
    }

    public function listReports(Request $request)
    {
        $reports = Report::paginate(10);

        return response()->json([
            'status'=>'success',
            'data' => $reports
        ],200);
    }
}
