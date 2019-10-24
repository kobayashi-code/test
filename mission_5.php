<?php
// データベースへ接続
$dsn='mysql:dbname=************;host=localhost;';
$user='*********';
$password='**********';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// ＊データベースに新規書き込み
if((isset($_POST["password"]))and(isset($_POST["name"]))and(isset($_POST["comment"]))and(isset($_POST["submitButton"]))){
    if(($_POST["password"]=="pass")and(!empty($_POST["name"]))and(!empty($_POST["comment"]))){
    $sql = $pdo -> prepare("INSERT INTO mission5_1 (userName,comment,userPassword,commentDate) VALUES (:userName,:comment,:userPassword,:commentDate)");
    $sql -> bindParam(':userName', $userName, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':userPassword', $userPassword,   PDO::PARAM_STR);
    $sql -> bindParam(':commentDate', $commentDate, PDO::PARAM_STR);

    $userName = $_POST["name"];
    $comment = $_POST["comment"]; 
    $userPassword =$_POST["password"];
    $commentDate = date('Y-m-d H:i:s');
    $sql -> execute();
    }
}

// ＊データベースからデータを削除
if (!empty($_POST["deleteLine"]) && isset($_POST["deleteButton"]) && $_POST["password"] == "pass"){
    $id = $_POST["deleteLine"];
	$sql = 'delete from mission5_1 where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

//編集するデータを選択
if(!empty($_POST["editLine"]) && isset($_POST["editButton"]) && $_POST["password"]== "pass"){

        $sql = 'SELECT * FROM mission5_1';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	    	if($row['id']==$_POST["editLine"]){
                $editName=$row['userName'];
                $editComment=$row['comment'];
            }
        }
    
}

// ＊データベースを編集
if(!empty($_POST["editNumber"]) && isset($_POST["submitButton"])){
    $id = $_POST["editNumber"]; 
	$userName = $_POST["name"];
	$comment = $_POST["comment"]; 
	$sql = 'update mission5_1 set userName=:userName,comment=:comment where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mission_5-6</title>
</head>
<body>
    <form action="" method = "post">
        <label for="name">お名前</label>
        <input type="text" name="name" placeholder="お名前" value="<?php if(isset($_POST["editButton"]) && $_POST["password"]=="pass" && isset($editName)){echo $editName;}?>">
        <br>
        <label for="comment">コメント</label>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($_POST["editButton"])&& $_POST["password"]=="pass" && isset($editComment)){echo $editComment;}?>">
        
        <input type="hidden" name="editNumber" value="<?php if(isset($_POST["editLine"])){echo $_POST["editLine"];} ?>">
        <br>
        <label for="password">パスワード</label>
        <input type="password" name="password" placeholder="パスワード">
        <br>
        <input type="submit" name="submitButton" value="送信する"> 
    </form>
        <br>
    <form action="" method="post">
        <label for="delete">削除対象番号</label>
        <input type="text" name="deleteLine" placeholder="削除する行を入力">
        <br>
        <label for="password">パスワード</label>
        <input type="password" name="password" placeholder="パスワード">
        <br>
        <input type="submit" name= "deleteButton" value="削除する">
    </form>
        <br>
        <br>
    <form action="" method="post">
        <label for="edit">編集対象番号</label>
        <input type="text" name="editLine" placeholder="編集する行を入力">
        <br>
        <label for="password">パスワード</label>
        <input type="password" name="password" placeholder="パスワード">
        <br>
        <input type="submit" name="editButton" value="編集する">
    </form>
    
    <!-- 掲示板の表示 -->
    <?php
    $sql = 'SELECT * FROM mission5_1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['userName'].',';
        echo $row['comment'].',';
        echo $row['commentDate'].'<br>';
        echo "<hr>";
    }
    ?>
</body>
</html>