<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<script>
    //一覧取得
    async function loadTodos() {
        const res = await fetch('/lists', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return await res.json();
    }

    //todoを<li>に入れる
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
        li.textContent = todo.title;
        // 削除ボタン
        const button = document.createElement('button');
        button.textContent = '削除';
        // li に追加していく
        li.appendChild(checkbox);
        li.appendChild(span);
        li.appendChild(button);
        ul.appendChild(li);
        });
   }

   //画面読み終わったら実行する
   document.addEventListener('DOMContentLoaded', async () => {
        const todos = await loadTodos();
        createTodos(todos);

        // フォームが送信されたら一覧を再読み込みする
        const form = document.getElementById('create-form');
        form.addEventListener('submit', async () => {
        const todos = await loadTodos();
        createTodos(todos);
        });
    });

</script>
<body>
    <form id="create-form" action="/create" method="POST">
        <input name="title">
        <input type="submit">
    </form>
    <ul id="todo-list"></ul>

        <input type="checkbox" name="status">
        </input>
</body>
</html>