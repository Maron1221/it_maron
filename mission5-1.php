<!DOCTYPE html>
<html lang="ja">

<head>
    <meta content="text/html; charset=utf-8" />
    <title>美咲の掲示板</title>
</head>

<body>

<?php
ini_set('display_errors', 1);

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
// DB接続設定
$sql = "CREATE TABLE IF NOT EXISTS test"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "password TEXT,"
	. "time TEXT"
	.");";
	$stmt = $pdo->query($sql);

//投稿機能
if (!empty($_POST['name'])
    && !empty($_POST['comment']) && !empty($_POST['pass'])&& empty($_POST['num'])) {
        
$sql = 'SELECT max(id) as id FROM test';  
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $result["id"]+1;
            
$sql = $pdo -> prepare("INSERT INTO test (id, name, comment, password, time) 
                   VALUES (:id, :name, :comment, :password, :time)");
	$sql -> bindParam(':id', $id, PDO::PARAM_INT);
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
	$sql -> bindParam(':time', $time, PDO::PARAM_STR);
	
	$name = $_POST['name'];
	$comment = $_POST['comment']; 
	$password=$_POST['pass'];
	$time=date('Y/m/d H:i:s');
	$sql -> execute();
    
        #var_dump($);
    }
    
//削除機能
if (!empty($_POST['deleteNo'])
    && !empty($_POST['pass'])) {
$id = $_POST['deleteNo'];
	$sql = 'delete from test where id=:id and password=:password';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->bindParam(':password',$_POST["pass"],PDO::PARAM_STR);
	$stmt->execute();
    }

//編集機能
#投稿フォームに該当番号の内容表示
if (!empty($_POST['dnum'])
    && !empty($_POST['pass'])) {

$id = $_POST['dnum']; //変更する投稿番号
$sql ='SELECT * FROM test WHERE id=:id and password=:password';
//これでidとパスワードが同じのを探している
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_POST["dnum"], PDO::PARAM_INT);
$stmt->bindParam(':password',$_POST["pass"],PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

        $name_edit = $result['name'];
        $comment_edit = $result['comment'];
        $id_edit = $result['id'];
        $edit_pass = $result['password'];
  }
  
  if(!empty($_POST['name'])
    && !empty($_POST['comment']) && !empty($_POST['pass']) && !empty($_POST['num'])) {
        
    $id=$_POST['num'];    
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$password=$_POST["pass"];
	//変更したい名前,コメント,パスワード
	$sql = 'UPDATE test SET name=:name,comment=:comment WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

}
        
    
?>


    <!--投稿フォーム-->
    　　
    掲示板
    <!--名前-->
    <form action="" method="post">
        <input type="text" name="name" value="<?php if (!empty($name_edit)) {
                                                    echo $name_edit;
                                                } ?>" placeholder="name"><br />                  
 <!--コメント-->
        <input type="text" name="comment" value="<?php if (!empty($comment_edit)) {
                                                        echo $comment_edit;
                                                    } ?>" placeholder="comment"><br>

        
        <!--編集番号確認のやつHIDDENに後でするやつ-->
        <input type="hidden" name="num" value="<?php if (!empty($id_edit)) {
                                                    echo $id_edit;
                                               } ?>">

        
        <!--パスワード-->
        <input type="text" name="pass" placeholder="password">

        <input type="submit" value="submit" />
    </form>
    <br>
    <!--削除フォーム-->
    <form action="" method="POST">
        <input type="text" name="deleteNo" placeholder="削除対象番号"><br>
        <input type="text" name="pass" placeholder="password">
        <input type="submit" name="delete" value="delete">
    </form>
    <br>
    <!--編集フォーム-->
    <form action="" method="post">
        <input type="text" name="dnum" placeholder="編集対象番号"><br>
        <input type="text" name="pass" placeholder="password">
        <input type="submit" name="edit" value="edit"><br>
    </form>
<?php
    $sql = 'SELECT * FROM test';
	$stmt = $pdo->query($sql);  // ←差し替えるパラメータを含めて記述したSQLを準備し、
	$results = $stmt->fetchAll(); // ←その差し替えるパラメータの値を指定してから、
	foreach ($results as $row){  // ←SQLを実行する。
	
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
	echo "<hr>";
	}
	?>
</body>

</html>