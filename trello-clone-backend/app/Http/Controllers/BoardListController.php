<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardList;
use Illuminate\Http\Request;

class BoardListController extends Controller
{
    public function store(Request $request, Board $board)
    {
        $this->authorizeBoard($request, $board);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // موقعیت جدید رو خودکار محاسبه کن (آخرین لیست + 1)
        $maxPosition = $board->lists()->max('position') ?? 0;

        $list = $board->lists()->create([
            'title' => $validated['title'],
            'position' => $maxPosition + 1,
        ]);

        return response()->json($list, 201);
    }

    public function update(Request $request, BoardList $list)
    {
        $this->authorizeList($request, $list);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
        ]);

        $list->update($validated);

        return response()->json($list);
    }

    public function destroy(Request $request, BoardList $list)
    {
        $this->authorizeList($request, $list);

        $list->delete();

        return response()->json(['message' => 'حذف شد.']);
    }

    private function authorizeBoard(Request $request, Board $board)
    {
        if ($board->workspace->owner_id !== $request->user()->id) {
            abort(403, 'دسترسی ندارید.');
        }
    }

    private function authorizeList(Request $request, BoardList $list)
    {
        if ($list->board->workspace->owner_id !== $request->user()->id) {
            abort(403, 'دسترسی ندارید.');
        }
    }
}