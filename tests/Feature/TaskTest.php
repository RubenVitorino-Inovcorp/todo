<?php

use App\Models\Task;
use App\Models\User;

test('tasks page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/tasks');

    $response->assertStatus(200);
});

test('user can create a task', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/tasks', [
        'title' => 'Test Task',
        'description' => 'Test Description',
        'priority' => 'high',
        'status' => 'pending',
        'due_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
    ]);

    $response->assertRedirect('/tasks');
    $this->assertDatabaseHas('tasks', [
        'title' => 'Test Task',
        'user_id' => $user->id,
    ]);
});

test('user can update their own task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Old Title',
    ]);

    $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
        'title' => 'New Title',
        'description' => 'Test Description Updated',
        'priority' => 'low',
        'status' => 'completed',
        'due_date' => now()->addDays(5)->format('Y-m-d H:i:s'),
    ]);

    $response->assertRedirect('/tasks');
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'New Title',
        'status' => 'completed',
    ]);
});

test('user cannot update other users task', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
        'title' => 'Stolen Task',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $response->assertStatus(403);
});

test('user can delete their own task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

    $response->assertRedirect('/tasks');
    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});

test('user cannot delete other users task', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
    ]);
});
