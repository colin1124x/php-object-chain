# colin/object-chain

php 物件/陣列 索引鍊

---

### install

設定依賴套件,並更新

```sh

    composer require colin/object-chain
    composer update

```

### test

執行單元測試

```sh
    
    phpunit

```

### sample

```php

use Colin\ObjectChain;

$data = new ObjectChain(json_decode('{"key":"hello","vary":{"depth":{"key":"found"}}}'));

$data->{'key'}->value();// return 'hello'
$data['key']->value();// return 'hello'

$data['vary']['depth']['key']->value();// return 'found'
$data['vary']['depth']['depth']['key']->value();// return null

```
