<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToDo</title>
    <style>
        /*完了したら取り消し線*/
        .done {
            text-decoration: line-through;
        }
        #todo-list {
            list-style: none;
        }
    </style>
</head>
<body data-current-folder-id="{{ $currentFolderId ?? '' }}">
    {{-- フォルダ --}}
    <section id="folder-area">
        <form id="folder-form">
            <input 
                id="folder-name" 
                name="name" 
                placeholder="フォルダ名を入力"
            >
            <button type="submit">フォルダ追加</button>
        </form>
        <ul id="folder-list"></ul>
    </section>
    {{-- Todo入力 --}}
    <form id="todo-form" action="/create" method="POST">
        <input name="title" id="title" placeholder="TODO入力">
        <input type="submit" value="追加">
    </form>
    {{-- Todo検索 --}}
    <form id="todo-search-form" action="/lists" method="GET">
        <input name="q" id="searchinput" placeholder="キーワードで検索">
        <input type="button" id="searchbtn" value="検索">
    </form>
    {{-- Todo一覧 --}}
    <ul id="todo-list"></ul>

    <script>
        //フォルダー持っておく箱
        let foldersCache = [];

        const currentFolderId = document.body.dataset.currentFolderId || '';

        //一覧取得
        async function loadTodos(q) {
            let url = '/lists';
            //パラメータを作る
            const params = new URLSearchParams();
            //フォルダ指定があるとき
            if (currentFolderId) {
                params.append('folder_id', currentFolderId);
            }
            //検索があるとき
            if (q && q.trim() !== '') {
                params.append('q', q.trim());
            }
            const queryString = params.toString();
            if (queryString !== '') {
                url = url + '?' + queryString;
            }
            //API実行
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
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
            if (todo.status) {
            span.classList.add('done');}
            //フォルダー選択プルダウン
            const folderSelect = document.createElement('select');
            const emptyOption = document.createElement('option');
            emptyOption.value = '';              
            emptyOption.textContent = 'フォルダなし';
            folderSelect.appendChild(emptyOption);
            foldersCache.forEach(folder => {
                const option = document.createElement('option');
                option.value = folder.id;;              
                option.textContent = folder.name;
                if (todo.folder_id == folder.id) {
                    option.selected = true;
                }
                folderSelect.appendChild(option);
            });
            //プルダウン変更されたら
            folderSelect.addEventListener('change', async(e) => {
                const newFolderId = e.target.value;
                try {
                    const response = await fetch(`/update/${todo.id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            folder_id: newFolderId === '' ? null : newFolderId
                        })
                    });
                    if (!response.ok) {
                        throw new Error('フォルダ更新に失敗しました (HTTP ' + response.status + ')');
                    }
                    alert('フォルダを変更しました');
                } catch (error) {
                    console.error(error);
                    alert('フォルダの更新に失敗しました');
                }
            });
            // 削除ボタン
            const del = document.createElement('span');
            del.textContent = '削除';
            del.style.color = 'red';
            del.style.cursor = 'pointer';
            // 削除処理
            del.addEventListener('click', async () => {
            if (confirm('本当に削除しますか？')) {
                await fetch(`/delete/${todo.id}`, { method: 'DELETE' });
                const todos = await loadTodos();
                createTodos(todos);
            }});
            // li に追加していく
            li.appendChild(checkbox);
            li.appendChild(span);
            li.appendChild(folderSelect);
            li.appendChild(del);
            ul.appendChild(li);
            });
        }

        // 完了処理
        document.getElementById('todo-list').addEventListener('change', async(e) => {
            if (e.target.type !== 'checkbox') {
                return;
            }
            const li = e.target.closest('li');
            if (!li) return;

            const id = li.dataset.id;   
            const checked = e.target.checked; 

            try{
                const response = await fetch(`/update/${id}`,{
                                    method: 'PATCH',
                                    headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ status: checked }),
                                });
                if (!response.ok) {
                throw new Error(`レスポンスステータス: ${response.status}`);
                }
                const json = await response.json();
            }catch (error) {
                console.error(error.message);
            }

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
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
                },
            body: JSON.stringify({ title })
        });

        const todos = await loadTodos();
        createTodos(todos);
        document.getElementById('title').value = '';
        });

        const searchInput = document.getElementById('searchinput');
        const searchBtn   = document.getElementById('searchbtn');

        //検索ボタン押されたとき
        searchBtn.addEventListener('click', async () => {
            const q = searchInput.value.trim(); 
            const todos = await loadTodos(q);  
            createTodos(todos);           
        });

        //検索入力欄でエンター押されたら検索ボタンが走る
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
            e.preventDefault(); 
            searchBtn.click(); 
            }
        });

        //フォルダー登録
        const folderForm = document.getElementById('folder-form');
        const folderNameInput = document.getElementById('folder-name');
        folderForm.addEventListener('submit', async (e) => {
            e.preventDefault(); 
            const name = folderNameInput.value.trim();
            if (!name) {
                alert('フォルダ名を入力してください');
                return;
            }
            try{
                const response = await fetch('/folders', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                        },
                                    body: JSON.stringify({ name })
                                });
                if (!response.ok) {
                throw new Error(`レスポンスステータス: ${response.status}`);
                }
                const json = await response.json();
                folderNameInput.value = '';
                
                const folders = await loadFolders();
                createFolders(folders);
                foldersCache = folders;
                const todos = await loadTodos();
                createTodos(todos);
            }catch (error) {
                console.error(error.message);
            }
        });

        //フォルダ一覧取得
        async function loadFolders() {
            const res = await fetch('/folders', { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return await res.json();
        }

        //フォルダ一覧表示
        function createFolders(folders) {
            const ul = document.getElementById('folder-list');
            ul.innerHTML = '';
            // 1件ずつ <li> を作って追加
            folders.forEach(folder => {
                const li = document.createElement('li');
                li.dataset.id = folder.id;
                // フォルダページに飛べるようにリンクをつける
                const link = document.createElement('a');
                link.href = `/folders/${folder.id}`;
                link.textContent = folder.name; 
                // li に追加していく
                li.appendChild(link);
                ul.appendChild(li);
            });
        }

        // 初回表示
        async function init() {
            const folders = await loadFolders();
            foldersCache = folders;
            createFolders(folders);

            const todos = await loadTodos();
            createTodos(todos);
        }
        init();
    </script>
</body>
</html>