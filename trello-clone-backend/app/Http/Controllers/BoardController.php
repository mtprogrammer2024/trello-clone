<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    // لیست بوردهای یه Workspace خاص
    public function index(Request $request, Workspace $workspace)
    {
        $this->authorizeWorkspace($request, $workspace);

        return response()->json($workspace->boards);
    }

    public function store(Request $request, Workspace $workspace)
    {
        $this->authorizeWorkspace($request, $workspace);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'background_color' => 'nullable|string|max:20',
        ]);

        $board = $workspace->boards()->create($validated);

        return response()->json($board, 201);
    }

    // نمایش یه بورد همراه لیست‌ها و کارت‌هاش (تو در تو)
    public function show(Request $request, Board $board)
    {
        $this->authorizeBoard($request, $board);

        return response()->json(
            $board->load('lists.cards')
        );
    }

    public function update(Request $request, Board $board)
    {
        $this->authorizeBoard($request, $board);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'background_color' => 'nullable|string|max:20',
        ]);

        $board->update($validated);

        return response()->json($board);
    }

    public function destroy(Request $request, Board $board)
    {
        $this->authorizeBoard($request, $board);

        $board->delete();

        return response()->json(['message' => 'حذف شد.']);
    }

    private function authorizeWorkspace(Request $request, Workspace $workspace)
    {
        if ($workspace->owner_id !== $request->user()->id) {
            abort(403, 'دسترسی ندارید.');
        }
    }

    private function authorizeBoard(Request $request, Board $board)
    {
        if ($board->workspace->owner_id !== $request->user()->id) {
            abort(403, 'دسترسی ندارید.');
        }
    }
}