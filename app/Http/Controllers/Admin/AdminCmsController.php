<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Benefit;
use App\Models\ContactMessage;
use App\Models\DataRequest;
use App\Models\Faq;
use App\Models\HowItWorksStep;
use Illuminate\Http\Request;

class AdminCmsController extends Controller
{
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

