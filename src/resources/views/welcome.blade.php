<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form id="todo-form" action="/create" method="POST">
        <input name="title" id="title">
        <input type="submit">
    </form>
    <ul id="todo-list"></ul>

    <script>
        //一覧取得
        async function loadTodos() {
            const res = await fetch('/lists', { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return await res.json();
        }

        //一覧表示
        function createTodos(todos) {
            const ul = document.getElementById('todo-list');
            ul.innerHTML = '';
            // 1件ずつ <li> を作って追加
            todos.forEach(todo => {
            const li = document.createElement('li');
            // チェックボックスを作る
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = todo.status;
            // タイトルを作る
            const span = document.createElement('span');
            span.textContent = todo.title;
            // 削除ボタン
            const button = document.createElement('button');
            button.textContent = '削除';
            // 削除ボタンが押されたとき
            button.addEventListener('click', async () => {
            if (confirm('本当に削除しますか？')) {
                await fetch(`/delete/${todo.id}`, { method: 'DELETE' });
                const todos = await loadTodos();
                createTodos(todos);
            }});
            // li に追加していく
            li.appendChild(checkbox);
            li.appendChild(span);
            li.appendChild(button);
            ul.appendChild(li);
            });
    }

        // 初回表示
        (async () => {
            const todos = await loadTodos();
            createTodos(todos);
        })();

    // フォーム送信
        document.getElementById('todo-form').addEventListener('submit', async (e) => {
        e.preventDefault(); 

        const title = document.getElementById('title').value;

        await fetch('/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ title })
        });

        const todos = await loadTodos();
        createTodos(todos);
        document.getElementById('title').value = '';
        });
    </script>
</body>
</html>