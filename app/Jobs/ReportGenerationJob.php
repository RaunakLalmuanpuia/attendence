<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Office;
use App\Models\Report;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private Report $report;
    private $fromDate;
    private $toDate;
    private Office $office;


    public function __construct(Report $report,Office $office,$fromDate,$toDate)
    {
        $this->report = $report;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->office = $office;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $office = Office::query()->find($this->office->id);
        $list=Attendance::query()->where('office_id', $this->office->id)
            ->with(['user','office','device'])
            ->whereDate('signin_at','>=', $this->fromDate)
            ->whereDate('signin_at', '<=', $this->toDate)
            ->get();
        $users = User::query()
        ->whereHas('offices', fn(Builder $builder) => $builder->where('offices.id',$this->office->id))
        ->orderBy('employee_no')
            ->get();

        $fileName = 'report/'.now()->timestamp . '.xlsx';
        Storage::disk('public')->put($fileName,'test');

        $writer = SimpleExcelWriter::create(Storage::disk('public')->path($fileName));
        $period = CarbonPeriod::create($this->fromDate, $this->toDate);

        foreach ($period as $date) {
            $writer->addNewSheetAndMakeItCurrent();
            $writer->nameCurrentSheet($date->format('d-m-Y'));
            $dateWiseAttendances = $list->filter(fn(Attendance $attendance) => $date->format('Y-m-d')===$attendance->signin_at->format('Y-m-d'));


            foreach ($users as $i=>$user) {
                $attendance = $dateWiseAttendances?->firstWhere('user_id', '=', $user->id);
                if (blank($attendance)) {
                    $writer->addRow([
                        'SN' => $i+1,
                        'Employee_no' => $user?->employee_no,
                        'Full Name' => $user?->full_name,
                        'Designation' => $user?->designation,
                        'Mobile' => $user?->mobile,
                        'Office' => $office->name,
                        'Signin Time' => '',
                        'Signout Time' => '',
                        'Device' => '',
                        'Present' => 'ABSENT',
                    ]);
                }else{
                    $writer->addRow([
                        'SN' => $i+1,
                        'Employee_no' => $attendance?->user?->employee_no,
                        'Full Name' => $attendance?->user?->full_name,
                        'Designation' => $attendance?->user?->designation,
                        'Mobile' => $attendance?->user?->mobile,
                        'Office' => $attendance?->office?->name,
                        'Signin Time' => $attendance?->signin_at?->format('d-m-Y h:i'),
                        'Signout Time' => $attendance?->signout_at?->format('d-m-Y h:i'),
                        'Device' => $attendance->device?->name,
                        'Present' => $attendance?->type===Attendance::PRESENT?'Present':'Late',
                    ]);
                }
            }
            flush();
        }
        $this->report->path = Storage::url($fileName);
        $this->report->status=Report::PROCESSED;
        $this->report->save();
    }
}
