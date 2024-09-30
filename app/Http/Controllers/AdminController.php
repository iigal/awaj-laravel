<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Comment;
use App\Models\Type;
use Illuminate\Support\Facades\Cookie;

class AdminController extends Controller
{
    // Application request
    public function applicationRequest() {
        $users = User::all();
        return view('admin.applicationRequest', compact('users'));
    }

    // Get types
    public function getType() {
        $database = Type::all();
        return view('admin.addCategory', compact('database'));
    }

    // Get complaints
    public function getComplaint(Request $request) {
        $status = $request->query('status');
        $area = $request->query('area');

        $query = Complaint::query();

        if ($status) {
            $query->where('status', $status);
        }
        if ($area) {
            $query->where('area', $area);
        }

        $all = $query->orderByDesc('_id')->get();
        $database = Type::all();
        return view('admin.complaint', compact('all', 'database'));
    }

    // Get complaint details
    public function getDetails($id) {
        $database = Type::all();
        $allComment = Comment::where('complaintId', $id)->with('user')->get();
        $complaint = Complaint::find($id);

        return view('admin.detail', [
            'payload' => $complaint,
            'isAdmin' => Cookie::get('admin'),
            'userName' => 'Admin',
            'userid' => Cookie::get('adminID'),
            'allComment' => $allComment,
            'database' => $database
        ]);
    }

    // Dashboard
    public function getDashboard() {
        $all = Complaint::orderByDesc('_id')->limit(10)->get();
        $everyData = Complaint::count();
        $successData = Complaint::where('status', 'Success')->count();
        $queueData = Complaint::where('status', 'Queue')->count();
        $progressData = Complaint::where('status', 'Progress')->count();

        return view('admin.dashboard', compact('all', 'everyData', 'successData', 'queueData', 'progressData'));
    }

    // Get user profile
    public function getProfile($id) {
        $user = User::find($id);
        $allComplaints = Complaint::where('userId', $id)->get();

        return view('admin.profile', [
            'userdata' => $user,
            'allComplaints' => $allComplaints,
            'userName' => Cookie::get('user'),
            'userid' => Cookie::get('userID'),
            'realuserid' => $id,
            'isAdmin' => Cookie::get('admin'),
        ]);
    }

    // Delete complaint
    public function deleteComplaint($id) {
        Complaint::findOrFail($id)->delete();
        return redirect('/admin/complaint');
    }

    // Delete image
    public function deleteImage(Request $request) {
        $id = $request->query('id');
        $imageDelete = $request->query('image');
        Complaint::where('_id', $id)->update(['images' => array_diff(Complaint::find($id)->images, [$imageDelete])]);
        return redirect("/admin/details/{$id}");
    }

    // Logout
    public function logout() {
        Cookie::queue(Cookie::forget('admin'));
        Cookie::queue(Cookie::forget('adminID'));
        return redirect('/');
    }

    // Post: Update complaint details
    public function updateDetails(Request $request, $id) {
        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'title' => $request->title,
            'description' => $request->description,
            'statusbar' => $request->progressbar,
            'statusmessege' => array_merge($complaint->statusmessege, [$request->progressmessege]),
            'status' => $request->status,
            'area' => $request->area,
        ]);

        return redirect('/admin/dashboard');
    }

    // Post: Update user application request
    public function updateApplicationRequest(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update(['accountstatus' => $request->submited]);

        return redirect('/admin/applicationrequest');
    }

    // Post: Add type
    public function addType(Request $request) {
        $area = strtolower($request->type);
        Type::create(['type' => $area]);

        return redirect('/admin/type');
    }

    // Post: Add comment
    public function addComment(Request $request, $complaintId) {
        Comment::create([
            'complaintId' => $complaintId,
            'message' => $request->comment,
            'userid' => Cookie::get('userID')
        ]);

        return redirect("/admin/details/{$complaintId}");
    }

    // Post: Reply to comment
    public function replyComment(Request $request, $complaintId, $commentId, $userid) {
        $comment = Comment::findOrFail($commentId);
        $comment->update([
            'reply' => array_merge($comment->reply, [
                [
                    'message' => $request->reply,
                    'userid' => $userid,
                    'username' => 'Admin',
                ]
            ])
        ]);

        return redirect("/admin/details/{$complaintId}");
    }
}
