<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only('search', 'sort');
        $searchQuery = $filters['search'] ?? $request->input('filters.search');

        // Limpar ordenações inválidas
        if (! in_array($filters['sort'] ?? null, ['titulo_asc', 'titulo_desc', 'vencimento_asc', 'vencimento_desc'], true)) {
            $filters['sort'] = 'titulo_asc';
        }

        $query = $request->user()->tasks();

        // Aplicar busca (search)
        if ($searchQuery) {
            $query->where('title', 'like', "%{$searchQuery}%");
        }

        // Aplicar ordenação (sort)
        switch ($filters['sort']) {
            case 'titulo_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'vencimento_asc':
                $query->orderBy('due_date', 'asc');
                break;
            case 'vencimento_desc':
                $query->orderBy('due_date', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'asc');
                break;
        }

        $tasks = $query->get();

        $pendingTasks = $tasks->where('status', 'pending')->values();
        $completedTasks = $tasks->where('status', 'completed')->values();

        $highPriorityTasks = $pendingTasks->where('priority', 'high')->values();
        $mediumPriorityTasks = $pendingTasks->where('priority', 'medium')->values();
        $lowPriorityTasks = $pendingTasks->where('priority', 'low')->values();

        return Inertia::render('tasks/Index', [
            'highPriorityTasks' => $highPriorityTasks,
            'mediumPriorityTasks' => $mediumPriorityTasks,
            'lowPriorityTasks' => $lowPriorityTasks,
            'completedTasks' => $completedTasks,
            'filters' => [
                'search' => $searchQuery,
                'sort' => $filters['sort'],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('tasks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $request->user()->tasks()->create($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        return Inertia::render('tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        return Inertia::render('tasks/Edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa removida com sucesso!');
    }
}
