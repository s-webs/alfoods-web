<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Task::query()->where('user_id', Auth::id());

        if ($request->has('date')) {
            $query->whereDate('due_date', $request->date);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderBy('due_date')->orderBy('created_at')->get();

        return response()->json($tasks);
    }

    public function getTodayTasks(): JsonResponse
    {
        $today = now()->format('Y-m-d');
        $tasks = Task::where('user_id', Auth::id())
            ->whereDate('due_date', $today)
            ->orderBy('created_at')
            ->get();

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => [
                'nullable',
                Rule::in([
                    Task::STATUS_CREATED,
                    Task::STATUS_IN_PROGRESS,
                    Task::STATUS_COMPLETED,
                    Task::STATUS_NOT_COMPLETED,
                ]),
            ],
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'status' => $validated['status'] ?? Task::STATUS_CREATED,
            'user_id' => Auth::id(),
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
            'status' => [
                'sometimes',
                Rule::in([
                    Task::STATUS_CREATED,
                    Task::STATUS_IN_PROGRESS,
                    Task::STATUS_COMPLETED,
                    Task::STATUS_NOT_COMPLETED,
                ]),
            ],
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}
