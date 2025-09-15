<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        /*完了したら取り消し線*/
        .done {
            text-decoration: line-through;
        }
    </style>
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
            li.dataset.id = todo.id;
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
            // 削除処理
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
        async function init() {
            const todos = await loadTodos();
            createTodos(todos);
        }
        init();

        // 完了処理
        document.getElementById('todo-list').addEventListener('change', async(e) => {
            const li = e.target.closest('li');
            if (!li) return;

            const id = li.dataset.id;   
            const checked = e.target.checked; 

            await fetch(`/update/${id}`,{
                method: 'PATCH',
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
                },
                body: new URLSearchParams({ status: checked ? 1 : 0})
            });

            // 取り消し線つける
            const span = li.querySelector('span');
            span.classList.toggle('done', checked);
        });

        // TODO追加処理
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