<div>
    @php
    $title = "All Feed";
    $breadcrumbs = ["Community", "Feed"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            @forelse ($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <img src="/{{$post->avatar}}" class="me-2 rounded-circle" width="40" />
                            <div class="">
                                <div>
                                    {{$post->name}} {{ !!$post->is_admin ? '(Team Alchemy)' : ''}}
                                </div>
                                <span class="text-muted">{{$post->created_at}}</>
                            </div>
                        </div>
                        <div>
                            @if (!$post->allow)
                            <a href="javascript:void;" class="fs-4" title="Allow">
                                <i class="mdi mdi-account-eye-outline"></i>
                            </a>
                            @endif
                            <a href="javascript:void;" class="fs-4" title="Edit">
                                <i class="uil uil-edit"></i>
                            </a>
                            <a href="javascript:void;" class="fs-4 text-danger" title="Delete">
                                <i class="uil  uil-trash-alt"></i>
                            </a>
                            @if ($post->pin)
                            <a href="javascript:void;" class="fs-4" title="Pinned">
                                <i class="mdi mdi-pin"></i>
                            </a>
                            @else
                            <a href="javascript:void;" class="fs-4" title="Set as pin">
                                <i class="mdi mdi-pin-outline"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="post-content">
                        @if (!!$post->is_admin && !!$post->type)
                        <div class="fs-4">Some great feedback about one of our tutors, {{$post->tagged_tutors[0]->tutor_name}}. Amazing work!</div>
                        @endif
                        <div class="my-1">{!! $post->content !!}</div>

                        @if (count($post->tagged_tutors) > 0)
                        <p class="text-muted">
                            👉&nbsp;&nbsp;
                            @foreach ($post->tagged_tutors as $tagged_tutor)
                            {{$tagged_tutor->tutor_name}}
                            @if (count($post->tagged_tutors) > 1) {!! ',nbsp;' !!} @endif
                            @endforeach
                        </p>
                        @endif

                        @if (!empty($post->file))
                        <img src="/{{$post->file}}" class="uploaded-img" />
                        @endif

                        @if ($post->post_like_total > 0 && $post->user_post_like)
                        <a href="javascript:void;" class="" title="Unlike">Unlike</a>
                        @else
                        <a href="javascript:void;" class="" title="Like">Like</a>
                        @endif

                        @if ($post->post_like_total > 0)
                        <div class="like-share-container">
                            <span>{{$post->post_like_total}} People reacted on this.</span>
                        </div>
                        @endif
                    </div>
                    <div class="comments-list mt-1">
                        @if (count($post->comments) > 0)
                        @foreach ($post->comments as $comment)
                        <div class="comment-item">
                            <div class="d-flex justify-content-between mb-2">
                                <img src="/{{$comment->avatar}}" class="me-2 rounded-circle" width="40" />
                                <div class="d-flex flex-grow-1 rounded-4 bg-light px-2 py-1 ">
                                    <div class="flex-grow-1">
                                        <div>
                                            <b>{{$comment->name}} {{ !!$comment->is_admin ? '(Team Alchemy)' : ''}}</b>
                                            <span class="">{!! $comment->content !!}</span>

                                            @if (!empty($comment->file))
                                            <img src="/{{$comment->file}}" class="uploaded-img" />
                                            @endif
                                        </div>
                                        <span class="text-muted">{{$comment->created_at}}</>

                                            @if ($comment->comment_like_total > 0 && $comment->user_comment_like)
                                            <a href="javascript:void;" class="" title="Unlike">Unlike</a>
                                            @else
                                            <a href="javascript:void;" class="" title="Like">Like</a>
                                            @endif

                                            @if ($comment->comment_like_total > 0)
                                            <div class="like-share-container d-inline-block">
                                                <span>{{$comment->comment_like_total}}</span>
                                            </div>
                                            @endif
                                    </div>
                                    <div class="">
                                        @if (!$comment->allow)
                                        <a href="javascript:void;" class="fs-5" title="Allow">
                                            <i class="mdi mdi-account-eye-outline"></i>
                                        </a>
                                        @endif
                                        <a href="javascript:void;" class="fs-5" title="Edit">
                                            <i class="uil uil-edit"></i>
                                        </a>
                                        <a href="javascript:void;" class="fs-5 text-danger" title="Delete">
                                            <i class="uil  uil-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="mt-2 bg-light p-2 rounded">
                        <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                            <div class="row">
                                <div class="col mb-2 mb-sm-0">
                                    <input type="text" class="form-control border-0" placeholder="Enter your text" required="">
                                    <div class="invalid-feedback">
                                        Please enter your messsage
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-light"><i class="uil uil-paperclip"></i></a>
                                        <a href="#" class="btn btn-light"> <i class="uil uil-smile"></i> </a>
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-success chat-send"><i class="uil uil-message"></i></button>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p>There are no posts</p>
            @endforelse
        </div>
    </div>
</div>

<script>

</script>