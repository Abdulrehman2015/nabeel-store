<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Benefit;
use App\Models\ContactMessage;
use App\Models\DataRequest;
use App\Models\Faq;
use App\Models\HomeSection;
use App\Models\HowItWorksStep;
use Illuminate\Http\Request;

class AdminCmsController extends Controller
{
    /**
     * Homepage Sections Manager
     */
    public function homeSections()
    {
        $sections = HomeSection::orderBy('sort_order')->get();
        return view('admin.cms.home_sections', compact('sections'));
    }

    public function reorderHomeSections(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:home_sections,id',
            'sections.*.title' => 'required|string',
            'sections.*.is_active' => 'nullable',
            'sections.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->input('sections') as $item) {
            HomeSection::where('id', $item['id'])->update([
                'title' => $item['title'],
                'is_active' => !empty($item['is_active']),
                'sort_order' => (int) $item['sort_order'],
            ]);
        }

        ActivityLog::log('cms.home_sections_updated');

        return redirect()->back()->with('success', 'Homepage sections updated successfully.');
    }

    /**
     * Contact Messages Inbox
     */
    public function contactMessages()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.cms.contact_messages', compact('messages'));
    }

    public function markMessageRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Message marked as read.');
    }

    /**
     * Data Requests Manager (Phase 8 Compliance)
     */
    public function dataRequests()
    {
        $requests = DataRequest::latest()->paginate(15);
        return view('admin.cms.data_requests', compact('requests'));
    }

    public function processDataRequest(Request $request, DataRequest $dataRequest)
    {
        $request->validate([
            'status' => 'required|in:processing,completed,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $dataRequest->update([
            'status' => $request->input('status'),
            'admin_notes' => $request->input('admin_notes'),
            'processed_at' => now(),
        ]);

        ActivityLog::log('data_request.processed', $dataRequest, ['status' => $request->input('status')]);

        return redirect()->back()->with('success', 'Data request status updated.');
    }
}
