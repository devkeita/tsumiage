@extends('layout')

@section('content')
    <div class="container mt-3">
        <div class="text-center">
            <h3 class="mb-3">
                {{ $today->isoFormat('YYYY年MM月DD日 (ddd)') }}
            </h3>
            @if(session('message'))
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
            @if($tasks)
                <table class="table mb-4">
                    <tr>
                        <th>タスク名</th>
                        <th>ステータス</th>
                    </tr>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>
                                @if($task->status)
                                    <i class="far fa-check-circle text-primary" style="font-size: 25px"></i>
                                @else
                                    <i class="far fa-times-circle text-secondary" style="font-size: 25px"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                <form action="{{ route('tweet.confirm') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="comment">ひとこと</label>
                        <textarea class="form-control" name="comment" id="comment" rows="5">{{ old('comment') }}</textarea>
                    </div>
                    <div class="d-flex justify-content-around">
                        <a class="btn btn-primary" href="{{ route('mypage') }}"><i class="fas fa-backward"></i> 戻る</a>
                        <button type="submit" class="btn btn-primary">確認画面へ <i class="fas fa-forward"></i></button>
                    </div>
                </form>
            @else
                <h4>タスクはありません</h4>
            @endif
        </div>
    </div>
@endsection