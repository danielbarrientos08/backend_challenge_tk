<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\UsersReport;
use App\Models\Report;
use Illuminate\Support\Carbon;
use App\Http\Requests\ReportGenerateRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function generateReport(ReportGenerateRequest $request)
    {
        $file_name = Str::slug($request->title,'-').'_'.time().'.xlsx';

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

    public function listReports()
    {
        $reports = Report::orderBy('created_at','DESC')->get();

        return response()->json([
            'status'=>'success',
            'response' => $reports
        ],200);
    }

    public function getReport(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if(Storage::disk('public')->exists($report->report_link))
        {
            $report_path= storage_path('app/public/'.$report->report_link);

            $headers = [
                'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];

            return response()->download($report_path, $report->title, $headers);
        }
        else
        {
            return response()->json([
                'status'=>'success',
                'message' => 'El reporte aún se esta procesando.'
            ],404);
        }
    }
}
