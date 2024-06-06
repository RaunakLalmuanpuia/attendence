<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\PostingRequest;
use App\Models\User;
use App\Models\UserOffice;
use App\Traits\CanFlashMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostingRequestController extends Controller
{
    use CanFlashMessage;

    public function create(Request $request)
    {
        $user = auth()->user();
        return inertia('Profile/PostingRequests', [
            'office' => $user->offices()->latest()->first(),
            'offices' => Office::query()->get(['id as value', 'name as label']),
            'postings' => $user->postingRequests()->with('office')->latest()?->get(),
        ]);
    }
    public function submitted(Request $request)
    {
        return inertia('Posting/Submitted', [
            'list' => PostingRequest::query()
                ->with(['office','user'])
                ->where('status',PostingRequest::SUBMITTED)
                ->latest()
                ->simplePaginate(),
        ]);
    }
    public function rejected(Request $request)
    {
        return inertia('Posting/Rejected', [
            'list' => PostingRequest::query()
                ->with(['office','user'])
                ->where('status',PostingRequest::REJECTED)
                ->latest()
                ->simplePaginate(),
        ]);
    }
    public function approved(Request $request)
    {
        return inertia('Posting/Approved', [
            'list' => PostingRequest::query()
                ->with(['office','user'])
                ->where('status',PostingRequest::APPROVED)
                ->latest()
                ->simplePaginate(),
        ]);
    }

    public function update(Request $request)
    {
        $data=$this->validate($request, [
            'office_id' => 'required'
        ]);
        $posting=PostingRequest::query()->create([
            'office_id'=>$data['office_id'],
            'user_id'=>auth()->id()
        ]);
        $this->flashMessage('Change office request submitted successfully','positive');
        return to_route('posting.index');
    }

    public function approve(Request $request, PostingRequest $model)
    {
        DB::transaction(function () use ($model) {
            $model->status = PostingRequest::APPROVED;
            $model->save();
            UserOffice::query()->create([
                'user_id'=>$model->user_id,
                'office_id'=>$model->office_id,
                'created_at'=>null,
                'updated_at' => null,
            ]);
        });
        $this->flashMessage('Posting request updated successfully');
        return back();
    }
    public function reject(Request $request, PostingRequest $model)
    {
        DB::transaction(function () use ($model) {
            $model->status = PostingRequest::REJECTED;
            $model->save();
        });
        $this->flashMessage('Posting request rejected successfully');
        return back();
    }
}
