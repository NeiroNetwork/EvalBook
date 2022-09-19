# EvalBook
本に書いたコードを実行するプラグイン

## コードの実行
スニークしながら専用の本をドロップすることでコードを実行します  
コードの実行には本に表示されている権限が必要です

## コマンド
コマンドの実行には `evalbook.group.operator` 権限が必要です

| コマンド | 説明 | エイリアス |
| --- | --- | --- |
| `/evalbook new` | EvalBookを手に入れます | `get` `give` |
| `/evalbook perm <default/op/everyone>` | EvalBookの実行権限を変更します | `permission` |
| `/evalbook reload` | 設定ファイルを再読み込みします |  |
| `/evalbook customname <any string>` | EvalBookのアイテムの名前を変更します | `name` |

## 権限
| 権限 | 本の表示 | 説明 |
| --- | --- | --- |
| `evalbook.group.operator` |  | `allowlist.txt` に記載されたプレイヤーに付与されます |
| `evalbook.exec.default` | `default` | `evalbook.group.operator` の権限がある場合はコードを実行できます |
| `evalbook.exec.op` | `op` | OPである場合のみコードを実行できます |
| `evalbook.exec.everyone` | `everyone` | 全員がコードを実行できます |

## コードの書き方
- ほとんどのクラスは自動的にインポートされるため、use文を書く必要はありません（書いてもエラーにはなりません）。  
  - しかし`pocketmine\item\Bed`や`pocketmine\block\Bed`のような同じ名前のクラスはどちらか一つだけがインポートされる(どちらがインポートされるかは不明な)ためuse文を書く必要があります。
- クラスや関数を定義するときは**1回だけ**定義するよう気を付けてください。
  - つまり、無名クラスや無名関数を使用することをおすすめします。
  - すでに定義されたクラスや関数を定義すると、エラーが発生しサーバーが終了します。
  - `try-catch`あるいは`set_exception_handler`では処理できません。
- クラスや関数に記述されたコードのエラーはキャッチしていません。~~(出来ない？)~~
  - イベントリスナーなどでエラーが出た場合はサーバーが終了します。

## コードの書き方の例
```php
/**
 * 以下の変数はコードを実行したプレイヤーが代入されています
 * $_player, $_PLAYER, $_player_, $_PLAYER_
 * $_executor, $_EXECUTOR, $_executor_, $_EXECUTOR_
 * $_executer, $_EXECUTER, $_executer_, $_EXECUTER_
 */
// コードを実行したプレイヤーにメッセージを送信します
$_player->sendMessage("本を実行しました");
```

### 良い書き方の例
```php
$listener = new class() implements Listener{
    function onJump(PlayerJumpEvent $event){
        $event->getPlayer()->sendTip("ジャンプしたよ");
    }
};

$this->getServer()->getPluginManager()->registerEvents($listener, $this);
```

### 悪い書き方の例
```php
// クラスを直接定義している、2回実行するとサーバーが落ちる
class MyEventListener implements Listener{
    function onChat(PlayerChatEvent $event){
        // 例外が発生するかもしれないコードをtry-catchで囲んでいない (例が悪い)
        // (class内のコードは例外がキャッチされず、サーバーがクラッシュする)
        $player = $this->getServer()->getPlayerByPrefix($event->getChat());
        $player->sendMessage("呼ばれたよ！");
    }
}

$this->getServer()->getPluginManager()->registerEvents(new MyEventListener(), $this);
```

### useを使わなければいけないコード
#### エラーになるコード
```php
use pocketmine\block\Bed;       // エラー！！！
use pocketmine\block\tile\Bed;  // エラー！！！
use pocketmine\item\Bed;        // エラー！！！
```

#### この書き方はOK
```php
use pocketmine\block\Bed as BlockBed;
use pocketmine\block\tile\Bed as TileBed;
use pocketmine\item\Bed as ItemBed;

if($_player->getWorld()->getBlock($_player->getPosition()) instanceof BlockBed){
    $_player->sendMessage("足元にベッド(ブロック)があるよ！");
}
if($_player->getWorld()->getTile($_player->getPosition()) instanceof TileBed){
    $_player->sendMessage("足元にベッド(タイル)があるよ！");
}
if($_player->getInventory()->getItemInHand() instanceof ItemBed){
    $_player->sendMessage("ベッド(アイテム)を手に持っているよ！");
}
```

#### `use ~ as ~;` の回避策
```php
if($_player->getInventory()->getItemInHand() instanceof \pocketmine\item\Bamboo){
    $_player->sendMessage("竹を手に持っているよ！");
}
```
