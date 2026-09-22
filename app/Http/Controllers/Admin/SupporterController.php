<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Supporter;
use App\Models\SupporterCategory;
use Illuminate\Http\Request;

/**
 * The supporters register — people backing the initiative who are not parents
 * here. Parents live on their own screen; they are supporters by definition
 * and never appear in this table.
 */
class SupporterController extends Controller
{
    public function index(Request $request)
    {
        $query = Supporter::with('categories');

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->string('category')->toString()) {
            $query->whereHas('categories', fn ($q) => $q->where('supporter_categories.id', $categoryId));
        }

        if ($request->boolean('inactive')) {
            $query->where('is_active', false);
        } else {
            $query->where('is_active', true);
        }

        return view('admin.supporters.index', [
            'supporters' => $query->orderBy('last_name')->orderBy('first_name')->paginate(25)->withQueryString(),
            'categories' => SupporterCategory::active()->ordered()->get(),
            'activeCount' => Supporter::active()->count(),
            'inactiveCount' => Supporter::where('is_active', false)->count(),
        ]);
    }

    public function show(Supporter $supporter)
    {
        $supporter->load('categories');

        return view('admin.supporters.show', [
            'supporter' => $supporter,
            'categories' => SupporterCategory::active()->ordered()->get(),
        ]);
    }

    public function updateCategories(Request $request, Supporter $supporter)
    {
        $validated = $request->validate([
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:supporter_categories,id'],
        ]);

        $supporter->categories()->sync($validated['categories'] ?? []);

        return back()->with('success', 'Support categories updated.');
    }

    public function deactivate(Request $request, Supporter $supporter)
    {
        $validated = $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $supporter->update(['is_active' => false]);

        AdminAuditLog::record('supporter.deactivate', $supporter, $validated['reason']);

        return back()->with('success', $supporter->full_name.' is no longer counted as a supporter.');
    }

    public function reactivate(Request $request, Supporter $supporter)
    {
        $supporter->update(['is_active' => true]);

        AdminAuditLog::record('supporter.reactivate', $supporter);

        return back()->with('success', $supporter->full_name.' is counted again.');
    }

    public function destroy(Request $request, Supporter $supporter)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'confirm_email' => ['required', 'string'],
        ]);

        if (strcasecmp($validated['confirm_email'], $supporter->email) !== 0) {
            return back()->with('error', 'Confirmation email did not match. Nothing was deleted.');
        }

        AdminAuditLog::record('supporter.delete', $supporter, $validated['reason'], [
            'email' => $supporter->email,
            'categories' => $supporter->categories->pluck('slug')->all(),
        ]);

        // Removes the record outright, category tags included.
        $supporter->delete();

        return redirect()
            ->route('admin.supporters.index')
            ->with('success', 'Supporter deleted.');
    }
}
