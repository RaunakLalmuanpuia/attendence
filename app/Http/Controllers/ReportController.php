<?php

namespace App\Http\Controllers;

use App\Jobs\ReportGenerationJob;
use App\Models\Attendance;
use App\Models\Office;
use App\Models\Report;
use App\Models\User;
use App\Traits\CanFlashMessage;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportController extends Controller
{
    use CanFlashMessage;
    public function index(Request $request)
    {
        $isAdmin = auth()->user()->hasAnyRole(['Admin']);
        $offices =$isAdmin? Office::query()->get(['name as label', 'id as value'])
        :  (auth()->user())->offices()->latest('user_offices.id')->get(['name as label','offices.id as value']);

        return inertia('Report/Index',[
            'offices'=>$offices,
            'fromDate'=>now()->subDays(value: 3)->format('Y-m-d'),
            'toDate'=>now()->format('Y-m-d'),
            'reports' => Report::query()->where('user_id',auth()->id())->simplePaginate(),
        ]);
    }

    private function makeThreadsold($list){
        $total = count($list);
        $threadsold = $total;
        if ($total > 200) {
            $threadsold = $total / 2;
        }
        if ($total > 500) {
            $threadsold = $total / 4;
        }
        if ($total > 1000) {
            $threadsold = $total / 100;
        }
        return $threadsold;
    }
    public function store(Request $request)
    {
        $officeId=$request->get('office_id');
        $userId=$request->get('user_id');
        $fromDate=$request->date('fromDate');
        $toDate=$request->date('toDate');

        if ($fromDate->diffInDays($toDate)>90) {
            $this->flashMessage('Maximum range of date must be 90 days','negative');
            return back();
        }
        $office = Office::query()->find($officeId);


        $report=Report::query()->create([
            'title' => 'Report Generated by ' . auth()->user()->full_name,
            'path' =>null,
            'user_id' => auth()->id(),
            'description' => 'Report generated for ' . $office->name . ' within the date of ' . $fromDate . '-' . $toDate,

        ]);
        Queue::later(1, new ReportGenerationJob($report, $office, $fromDate, $toDate));
//        dispatch(new ReportGenerationJob($report,$office,$fromDate,$toDate));

        $this->flashMessage('Report Generation is in process, Please come back later','positive');

        return to_route('report.index');
    }

    public function users(Request $request, Office $office)
    {
        return [
            'list' => $office->users()->get(['full_name as label', 'users.id as value'])
        ];
    }

    public function makeReport(Request $request)
    {
        $writer = SimpleExcelWriter::create(storage_path().'/test.xlsx');

        for ($i = 0; $i < 100; $i++) {
            $writer->addNewSheetAndMakeItCurrent();
            $writer->nameCurrentSheet($i);
            for ($j=0;$j<100;$j++) {
                $writer->addRow([
                    'j' => 'J value: '.$j.' i value:'.$i
                ]);
            }
        }
//        Posts::all()->each(function (Post $post) use ($writer) {
//
//
//            $post->comments->each(function (Comment $comment) use ($writer) {
//                $writer->addRow([
//                    'comment' => $comment->comment,
//                    'author' => $comment->author,
//                ]);
//            });
//
//            if(!$post->is($posts->last())) {
//                $writer->addNewSheetAndMakeItCurrent();
//            }
//        });
    }
}
