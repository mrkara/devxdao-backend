<?php

namespace App\Http\Controllers\Discourse;

use App\Http\Controllers\Controller;
use App\Services\DiscourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicPostController extends Controller
{
    public function index(Request $request, DiscourseService $discourse, $topic)
    {
        $posts = $discourse->postsByTopicId(
            $topic,
            $request->input('post_ids'),
            $discourse->getUsername(Auth::user())
        );

        $posts = collect($discourse->mergeWithFlagsAndReputation($posts));

        return ['success' => true, 'data' => $posts];
    }

    public function store(Request $request, DiscourseService $discourse, $topic)
    {
        $data = [
            'raw' => $request->post,
            'topic_id' => $topic,
        ];

        if ($request->has('reply_to_post_number')) {
            $data['reply_to_post_number'] = $request->reply_to_post_number;
        }

        return $discourse->createPost($data, $discourse->getUsername(Auth::user()));
    }
}
