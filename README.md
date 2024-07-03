# EvalBook

Minecraft ゲーム内でコードを本に書いて実行できるプラグイン

## 権限について

コマンドの実行や EvalBook に書かれたコードの実行には専用の権限が必要です。  
`plugin_data/EvalBook/allowlist.txt` に名前を書くことで自動的に権限が付与されます。  
ファイルをリロードするには `/evalbook reload` を実行してください。

## コマンド

| コマンド                                   | 説明                         | エイリアス        |
|----------------------------------------|----------------------------|--------------|
| `/evalbook get`                        | EvalBookを手に入れます            | `new`        |
| `/evalbook give [player]`              | 指定したプレイヤーにEvalBookを与えます    |              |
| `/evalbook perm <default/op/everyone>` | 手持ちのEvalBookの実行権限を変更します    | `permission` |
| `/evalbook reload`                     | `allowlist.txt` を再読み込みします  |              |
| `/evalbook name <any string>`          | 手持ちのEvalBookのアイテムの名前を変更します | `customname` |
| `/evalbook edit`                       | 手持ちの署名されたEvalBookを元に戻します   | `revert`     |

## コードの実行方法

スニークしながら EvalBook と呼ばれる専用の本をドロップすることでコードを実行します。  
コードの実行には本に表示されている権限 (デフォルトは `evalbook.group.operator`) が必要です。

## コードの書き方

### クラスのインポート (use文) について

PocketMine-MP に存在するクラスについては、自動的にインポート文が挿入されるため書く必要はありません。  
ただし、`pocketmine\item\Bed`や`pocketmine\block\Bed` のような同じ名前のクラスは、以下のリストに載っているクラスを除き、インポートされません。

| 優先的にインポートされるクラス                                                |
|----------------------------------------------------------------|
| `pocketmine\Server`                                            |
| `pocketmine\player\GameMode`                                   |
| `pocketmine\network\mcpe\protocol\serializer\PacketSerializer` |
| `Ramsey\Uuid\Uuid`                                             |
| `Ramsey\Uuid\UuidInterface`                                    |

### コードのエラーについて

EvalBookによるコードで発生したエラーはキャッチされ、実行者あるいは全体に通知されます。  
ただし、fatal error が発生した場合はエラーがキャッチされず、サーバーがクラッシュします。

#### fatal error の例

:warning: これらは `try-catch` や `set_exception_handler` を使ってもキャッチできません。

- 同じ名前のクラスや関数を2回以上定義しようとしたとき
- クラスの誤った継承(extends)や実装(implements)
- 同じクラス名を複数回インポートしようとしたとき

## 特殊な変数・関数について

### `$_player` などの変数

コードを実行した**プレイヤー**があらかじめ代入されています。以下にリストされる12つの変数が予約されています。

```php
$_player, $_PLAYER, $_player_, $_PLAYER_,
$_executor, $_EXECUTOR, $_executor_, $_EXECUTOR_,
$_executer, $_EXECUTER, $_executer_, $_EXECUTER_
```

### 関数: `var_dump_p(Player $player, mixed ...$value) : void`

`var_dump()` の結果をプレイヤーに送信します。

```php
var_dump_p($_player, "Hello EvalBook!");
```

## コードの書き方の例

```php
// コードを実行したプレイヤーにメッセージを送信します
$_player->sendMessage("本を実行しました");
```

```php
// ジャンプしたらtipを送信します
$listener = new class() implements Listener{
    public function onJump(PlayerJumpEvent $event) : void{
        $event->getPlayer()->sendTip("ジャンプしたよ");
    }
};
$this->getServer()->getPluginManager()->registerEvents($listener, $this);
```

```php
// PluginManager->registerEvents() を使わないバージョン
$onJump = function(PlayerJumpEvent $event) : void{
    $event->getPlayer()->sendTip("ジャンプしたよ");
};
$this->getServer()->getPluginManager()->registerEvent(PlayerJumpEvent::class, $onJump, EventPriority::NORMAL, $this);
```
