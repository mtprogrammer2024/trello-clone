<?php

namespace App\Http\Controllers;

use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request, BoardList $list)
    {
        $this->authorizeList($request, $list);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $maxPosition = $list->cards()->max('position') ?? 0;

        $card = $list->cards()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'position' => $maxPosition + 1,
        ]);

        return response()->json($card, 201);
    }

    public function show(Request $request, Card $card)
    {
        $this->authorizeCard($request, $card);

        return response()->json($card);
    }

    public function update(Request $request, Card $card)
    {
        $this->authorizeCard($request, $card);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $card->update($validated);

        return response()->json($card);
    }

    // جابجایی کارت بین لیست‌ها (برای Drag & Drop، بعداً استفاده میشه)
    public function move(Request $request, Card $card)
    {
        $this->authorizeCard($request, $card);

        $validated = $request->validate([
            'board_list_id' => 'required|exists:board_lists,id',
            'position' => 'required|integer',
        ]);

        $card->update($validated);

        return response()->json($card);
    }

    public function destroy(Request $request, Card $card)
    {
        $this->authorizeCard($request, $card);

        $card->delete();

        return response()->json(['message' => 'حذف شد.']);
    }

    private function authorizeList(Request $request, BoardList $list)
    {
        if ($list->board->workspace->owner_id !== $request->user()->id) {
            abort(403, 'دسترسی ندارید.');
        }
    }

    private function authorizeCard(Request $request, Card $card)
    {
        if ($card->list->board->workspace->owner_id !== $request->user()->id) {
            abort(403, 'دسترسی ندارید.');
        }
    }
}