<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Public)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (All authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== STAFF ONLY: Request Items =====
    Route::get('/requests', [RequestController::class, 'catalog'])->name('requests.catalog');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');

    // ===== ADMIN ONLY: Inventory + Approvals + Logs =====
    Route::middleware('admin')->group(function () {
        // Inventory CRUD
        Route::get('/inventory', [ItemController::class, 'index'])->name('items.index');
        Route::post('/inventory', [ItemController::class, 'store'])->name('items.store');
        Route::put('/inventory/{id}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/inventory/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::post('/inventory/{id}/stock-in', [ItemController::class, 'stockIn'])->name('items.stockIn');
        Route::post('/inventory/{id}/stock-out', [ItemController::class, 'stockOut'])->name('items.stockOut');

        // Activity Logs
        Route::get('/inventory/logs', [ItemController::class, 'logs'])->name('items.logs');

        // Manage Requests (Approve/Reject)
        Route::get('/requests/manage', [RequestController::class, 'manage'])->name('requests.manage');
        Route::post('/requests/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    });

    // Notification AJAX (Accessible by both Admin and Staff)
    Route::post('/notifications/{id}/read', [RequestController::class, 'markRead'])->name('notifications.read');
});
