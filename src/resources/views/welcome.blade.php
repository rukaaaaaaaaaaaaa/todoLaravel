<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<script>
  fetch('/lists', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
    .then(res => {
      if (!res.ok) throw new Error('HTTP ' + res.status);
      return res.json();
    })
    .then(data => {
      console.log('API結果:', data);
    })
    .catch(err => {
      console.error('取得失敗:', err);
    });
</script>
<body>
    <form action="/create" method="POST">
        <input name="title">
        </input>
        <input type="submit">
        </input>
    </form>
    <
        <input type="checkbox" name="status">
        </input>
</body>
</html>