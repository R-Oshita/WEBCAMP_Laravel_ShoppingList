<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>買い物リスト 管理画面 @yield('title')</title>
</head>
<body>

   <div>

        @auth('admin')
        <menu label="リンク">
            <a href="/admin/top">管理画面Top</a><br>
            <a href="/admin/user/list">ユーザ一覧</a><br>
            <a href="/admin/logout">ログアウト</a><br>
        </menu>
        @endauth

    </div>
    <!-- コンテンツ -->
     @yield('contents')
</body>
</html>