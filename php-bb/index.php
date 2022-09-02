<?php

date_default_timezone_set('Asia/Tokyo');
$comment_array = array();
$pdo=null;
$stmt = null;

$error_messages = array();

//DB接続のコード、ドキュメントから
try {
    $pdo = new PDO('mysql:host=localhost;dbname=php-bb', "root", "");
} catch (PDOException $e) {
    echo $e->getMessage();
}



//フォームを打ち込んだ時
if(!empty($_POST["submitButton"])) {

    //名前とコメントがない時に打ち込めないようにするため
    if(empty($_POST["username"])) {
        echo "";
        $error_messages["username"]="";
    }

    if(empty($_POST["comment"])) {
        echo "";
        $error_messages["comment"]="";
    }


    if (empty($error_messages)){
        //年、月、日、時間、分、秒
        $postDate = date("Y-m-d H:i:s");
        //stmtはドキュメントから
        try {
            $stmt = $pdo->prepare("INSERT INTO `php-bb-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate);");
            $stmt->bindParam(':username', $_POST['username'],PDO::PARAM_STR);
            $stmt->bindParam(':comment', $_POST['comment'],PDO::PARAM_STR);
            $stmt->bindParam(':postDate', $postDate,PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}



//DBからコメントデータを取得する
$sql = "SELECT `id`, `username`, `comment`, `postDate` FROM `php-bb-table`;";
$comment_array = $pdo->query($sql);

//DB接続閉じる
$pdo = null;


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-, initial-scale=1.0">
    <meta name=”viewport” content="width=device-width, initial-scale=1.0">
    <meta name=”viewport” content="width=device-width">
    <meta name=”viewport” content="initial-scale=1.0">
    <meta name=”viewport” content="initial-scale=1.0, user-scalable=no">

    <title>掲示板</title>
    <link rel="stylesheet" href="style.css">
    

</head>
<body>
    <h1 class="title">掲示板</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach ($comment_array as $comment) : ?>
            <article>
                <div class="wrapper">
                    <div class="nameArea">
                        <span>名前：</span>
                        <p class="username"><?php echo $comment["username"]; ?></p>
                        <time><?php echo $comment["postDate"]; ?></time>
                    </div>
                    <p class="comment"><?php echo $comment["comment"]; ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </section>
        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="usernameLabel">名前：</label>
                <input tupe="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
    </div>
    <hr>
    
</body>
</html>