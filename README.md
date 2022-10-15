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
### クラスのインポート (use文) について
ほとんどのクラスは自動的にインポートされるため、インポート(use)文を書く必要はありません。  
また、重複したインポート文を取り除く努力は行いますが、完全ではありません(#10)。  
しかし、`pocketmine\item\Bed`や`pocketmine\block\Bed`のような同じ名前のクラスは、混乱を避けるため(以下のリストに載っているものを除き)基本的にインポートされません。

| 優先的にインポートされるクラス                                                |
|----------------------------------------------------------------|
| `pocketmine\Server`                                            |
| `pocketmine\player\GameMode`                                   |
| `pocketmine\network\mcpe\protocol\serializer\PacketSerializer` |
| `Ramsey\Uuid\Uuid`                                             |
| `Ramsey\Uuid\UuidInterface`                                    |

### コードのエラーについて
EvalBookによって実行されたコードで発生したエラーはキャッチされ、実行者に表示されます。
ただし、以下のような場合はエラーがキャッチされず、サーバーがクラッシュします。
- 致命的なエラー (fatal error) が発生した時
- PocketMine-MP によって関数がコールされ、処理された中でエラーが発生した時
  - イベントリスナー
  - スケジューラ―
  - など…

### 致命的なエラー (fatal error) について
`try-catch` や `set_exception_handler` などの関数でキャッチできないエラーです。
- 同じ名前のクラスや関数が複数回定義する
  - クラスや関数を定義するときは**1回だけ**定義するよう気を付けてください。
  - つまり、無名クラスや無名関数を使用することをおすすめします。
- 誤ったクラスやインターフェースの継承(extends)、実装(implements)
- 同じクラス名のインポートを複数回行う
- など…

## 特殊な変数・関数について
### `$_player` などの変数
コードを実行したプレイヤーがあらかじめ代入されています。以下にリストされる12つの変数が予約されています。
```php
$_player, $_PLAYER, $_player_, $_PLAYER_,
$_executor, $_EXECUTOR, $_executor_, $_EXECUTOR_,
$_executer, $_EXECUTER, $_executer_, $_EXECUTER_
```

### 関数: `var_dump_p(Player $player, mixed ...$value) : void`
`var_dump()` の結果をプレイヤーに送信します。

## コードの書き方の例
```php
// コードを実行したプレイヤーにメッセージを送信します
$_player->sendMessage("本を実行しました");

// ジャンプしたらtipを送信します
$listener = new class() implements Listener{
    public function onJump(PlayerJumpEvent $event) : void{
        $event->getPlayer()->sendTip("ジャンプしたよ");
    }
};
$this->getServer()->getPluginManager()->registerEvents($listener, $this);
```

### 悪い書き方の例
```php
// クラスを複数回定義してしまう可能性があります
// class_exists() 関数を使って1度だけ定義するなどの対策を取りましょう
class MyEventListener implements Listener{
    // 例えば、メンションされたプレイヤーを取得したい としましょう
    public function onChat(PlayerChatEvent $event) : void{
        /**
         * 以下に、ミスの例を記載します
         * IDEやテキストエディタを使用すれば、ある程度のミスは防げますが
         * try-catch で囲うなどした方が良いでしょう
         */
        // getMessage を getMesasge とタイポしています
        $name = substr($event->getMesasge(), 1);
        // $this->getServer() という関数は存在しません
        $target = $this->getServer()->getPlayerByPrefix();
        // プレイヤーが存在するかどうかチェックしていません
        $target->sendMessage("メンションされた！");
    }
}
```
