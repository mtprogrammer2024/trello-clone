<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    // لیست همه Workspace های کاربر لاگین‌شده
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->workspaces
        );
    }

    // ساخت Workspace جدید
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $workspace = $request->user()->workspaces()->create($validated);

        return response()->json($workspace, 201);
    }

    // نمایش یه Workspace خاص همراه بوردهاش
    public function show(Request $request, Workspace $workspace)
    {
        $this->authorizeAccess($request, $workspace);

        return response()->json($workspace->load('boards'));
    }

    // ویرایش
    public function update(Request $request, Workspace $workspace)
    {
        $this->authorizeAccess($request, $workspace);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $workspace->update($validated);

        return response()->json($workspace);
    }

    // حذف
    public function destroy(Request $request, Workspace $workspace)
    {
        $this->authorizeAccess($request, $workspace);

        $workspace->delete();

        return response()->json(['message' => 'حذف شد.']);
    }

    // چک می‌کنه که کاربر لاگین‌شده صاحب این Workspace هست یا نه
    private function authorizeAccess(Request $request, Workspace $workspace)
    {
        if ($workspace->owner_id !== $request->user()->id) {
            abort(403, 'شما دسترسی به این فضای کاری ندارید.');
        }
    }
}