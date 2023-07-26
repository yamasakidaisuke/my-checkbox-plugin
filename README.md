# my-checkbox-plugin
読了管理チェックリスト（社内用）

## 手順
- カスタムフィールドに読了管理フィールドグループを作る
- （グループキーはPHPを変更する必要あり）
- フィールド名の例はmy_checkbox_name_01とする
- カスタムフィールド設定の「このフィールドグループを表示する条件」にチェックボックス管理用の固定ページを設定する
- チェックボックス管理用の固定ページにチェックボックス項目を追加する。（見本はAさん）
- 固定ページのpost_id="20"を覚えておく
- 表示したいページにショートコードを貼り付ける [my_checkboxes post_id="20"]
- チェックボックスを表示したいページにショートコードを貼り付ける [display_all_checkboxes post_id="20"]

## 仕様
- チェックボックスがフロントエンドに表示される
- チェックボックスの増減ができる（ACFで）
- 一覧でチェックボックスの有無が確認できる
