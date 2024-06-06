<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'attachment' => ['required', Rule::file()->max(2222222)]
        ]);
        $file = $request->file('attachment');
        $path=Storage::disk('public')->put('attachments',$file);
        $attachment=Attachment::query()->create([
            'name' => $file->getClientOriginalName(),
            'path'=>$path
        ]);
        return response()->json([
            'data' => $attachment,
            'message' => 'File uploaded successfully'
        ]);
    }

    public function destroy(Request $request, Attachment $model)
    {
        Storage::disk('public')->delete($model->path);
        $model->delete();

        return response()->json([
            'data' => $model,
            'message' => 'File deleted successfully'
        ]);
    }
}
