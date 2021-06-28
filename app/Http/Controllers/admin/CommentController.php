<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    public function index(Request $request){
        $visibilityStatus = $request->get('visibilityStatus') ?? -1;
        $search = $request->get('search') ?? null;

        $comments = Comment::with('get_step.get_course')->where('onSection' , 3);

        if (isset($search)){
            $comments = $comments
            ->where([
                [ 'comment' , 'LIKE' , '%'.$search.'%' ]
            ]);
        }

        if ($visibilityStatus >= 0){
            $comments = $comments
                ->where([
                    [ 'visibilityStatus' , $visibilityStatus ]
                ]);
        }


        $comments = $comments->orderBy('date' , 'desc')->paginate(10);
        $comments->appends(['visibilityStatus' => $visibilityStatus]);
        $comments->appends(['search' => $search]);


        return view('admin.comment.index' , compact('comments' , 'visibilityStatus'));

    }


    public function edit($id){
        $comment = Comment::with('get_step.get_course')->findOrFail($id);

        if($comment->get_step && $comment->get_step->get_course){
            return view('admin.comment.edit' , compact('comment'));
        }
        else{
            abort(404);
        }
    }


    public function update($id , Request $request){
        $comment = Comment::findOrFail($id);
        $comment->comment = $request->comment;
        $comment->visibilityStatus = $request->visibilityStatus;
        $comment->EditedAt = time();
        $comment->isNew = 0;
        $comment->save();

        if($request->reply){
            $reply_comment = new Comment;
            $reply_comment->comment = $request->reply;
            $reply_comment->date = time();
            $reply_comment->user_id = Auth::id();
            $reply_comment->sender = Auth::user()->mobile;
            $reply_comment->receiver = '';
            $reply_comment->replyToID = $comment->id;
            $reply_comment->onSection = 3;
            $reply_comment->onIDofSection = $comment->onIDofSection;
            $reply_comment->repliersId = 0;
            $reply_comment->isReported = 0;
            $reply_comment->EditedAt = time();
            $reply_comment->visibilityStatus = 1;
            $reply_comment->isNew = 0;
            $reply_comment->save();
        }


        $request->session()->flash('success', 'کامنت با موفقیت ویرایش شد');

        return redirect()->back();
    }

    public function replyUpdate($id , Request $request){
        $comment = Comment::findOrFail($id);
        $comment->comment = $request->comment;
        $comment->visibilityStatus = $request->visibilityStatus;
        $comment->EditedAt = time();
        $comment->isNew = 0;
        $comment->save();

        $request->session()->flash('success', 'کامنت با موفقیت ویرایش شد');

        return redirect()->back();

    }

    public function destroy($id){
        $comment = Comment::findOrFail($id);
        $comment->visibilityStatus = 0;
        $comment->save();

        Session::flash('success', 'کامنت با موفقیت حذف شد');

        return redirect()->back();
    }
}
