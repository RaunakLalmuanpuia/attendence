<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Traits\CanFlashMessage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use CanFlashMessage;
    public function index(Request $request)
    {
        return inertia('Page/Index', [
            'list' => Page::all(),
        ]);
    }

    public function create(Request $request)
    {
        return inertia('Page/Create', [
            'options'=>[Page::PRIVACY,Page::TERM],
        ]);
    }
    public function store(Request $request)
    {
        $data=$this->validate($request, [
            'title' => 'required',
            'type' => 'required',
            'content' => 'required',
        ]);
        Page::query()->create($data);
        $this->flashMessage('Page updated successfully');

        return to_route('page.index');
    }

    public function edit(Request $request,Page $page)
    {
        return inertia('Page/Edit', [
            'options'=>[Page::PRIVACY,Page::TERM],
            'data'=>$page
        ]);
    }

    public function update(Request $request,Page $page)
    {
        $data=$this->validate($request, [
            'title' => 'required',
            'type' => 'required',
            'content' => 'required',
        ]);
        $page->update($data);
        $this->flashMessage('Page updated successfully');

        return to_route('page.index');
    }

    public function destroy(Request $request, Page $page)
    {
        $page->delete();
        $this->flashMessage('Page deleted successfully');

        return to_route('page.index');
    }

    public function privacy(Request $request)
    {
        return inertia('Page/Privacy', [
            'data' => Page::query()->where('type', Page::PRIVACY)->first(),
        ]);
    }

    public function term(Request $request)
    {
        return inertia('Page/Term', [
            'data' => Page::query()->where('type', Page::TERM)->first(),
        ]);
    }

}
