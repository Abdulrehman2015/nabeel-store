<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Page;
use App\Models\PageVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPolicyController extends Controller
{
    public function index()
    {
        $policies = Page::orderBy('sort_order')->get();
        return view('admin.policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.policies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:pages,slug',
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
            'show_in_footer' => 'nullable|boolean',
            'show_at_checkout' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'change_summary' => 'nullable|string|max:255',
            // SEO
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:300',
            'robots' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_in_footer'] = $request->boolean('show_in_footer');
        $validated['show_at_checkout'] = $request->boolean('show_at_checkout');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['current_version'] = 1;

        $page = Page::create($validated);

        // Record initial version (Phase 8)
        PageVersion::create([
            'page_id' => $page->id,
            'version_number' => 1,
            'title' => $page->title,
            'content' => $page->content,
            'created_by' => auth()->id(),
            'change_summary' => $request->input('change_summary') ?: 'Initial creation',
        ]);

        ActivityLog::log('policy.created', $page, ['title' => $page->title]);

        return redirect()->route('admin.policies.index')->with('success', 'Policy page created with Version 1.0.');
    }

    public function edit(Page $policy)
    {
        $policy->load('versions.author');
        return view('admin.policies.edit', compact('policy'));
    }

    public function update(Request $request, Page $policy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:pages,slug,' . $policy->id,
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
            'show_in_footer' => 'nullable|boolean',
            'show_at_checkout' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'change_summary' => 'nullable|string|max:255',
            // SEO
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:300',
            'robots' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_in_footer'] = $request->boolean('show_in_footer');
        $validated['show_at_checkout'] = $request->boolean('show_at_checkout');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // If content changed, increment version number and save version record (Phase 8)
        if ($policy->content !== $validated['content'] || $policy->title !== $validated['title']) {
            $newVersionNumber = $policy->current_version + 1;
            $validated['current_version'] = $newVersionNumber;

            PageVersion::create([
                'page_id' => $policy->id,
                'version_number' => $newVersionNumber,
                'title' => $validated['title'],
                'content' => $validated['content'],
                'created_by' => auth()->id(),
                'change_summary' => $request->input('change_summary') ?: 'Updated version ' . $newVersionNumber . '.0',
            ]);
        }

        $policy->update($validated);
        ActivityLog::log('policy.updated', $policy, ['title' => $policy->title, 'version' => $policy->current_version]);

        return redirect()->route('admin.policies.index')->with('success', 'Policy updated (Version ' . $policy->current_version . '.0).');
    }

    /**
     * Restore previous version of a policy page (Phase 8).
     */
    public function restoreVersion(Page $policy, int $versionNumber)
    {
        $version = PageVersion::where('page_id', $policy->id)
            ->where('version_number', $versionNumber)
            ->firstOrFail();

        $policy->update([
            'title' => $version->title,
            'content' => $version->content,
            'current_version' => $versionNumber,
        ]);

        ActivityLog::log('policy.version_restored', $policy, ['restored_version' => $versionNumber]);

        return redirect()->back()->with('success', 'Policy page restored to Version ' . $versionNumber . '.0.');
    }

    public function destroy(Page $policy)
    {
        ActivityLog::log('policy.deleted', $policy, ['title' => $policy->title]);
        $policy->delete();

        return redirect()->route('admin.policies.index')->with('success', 'Policy deleted.');
    }
}
